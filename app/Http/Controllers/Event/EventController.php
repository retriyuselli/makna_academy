<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Event\PaymentProcessor;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    public function show(Event $event): View
    {
        $relatedEvents = Event::where('event_category_id', $event->event_category_id)
            ->where('id', '!=', $event->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('event.show', compact('event', 'relatedEvents'));
    }

    public function showRegistrationForm(Event $event): View
    {
        if ($event->current_participants >= $event->max_participants) {
            abort(404, 'Event sudah penuh');
        }

        $alreadyRegistered = false;
        if (Auth::check()) {
            $alreadyRegistered = EventRegistration::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('event.daftar', compact('event', 'alreadyRegistered'));
    }

    public function register(Request $request, Event $event): RedirectResponse
    {
        if ($event->current_participants >= $event->max_participants) {
            return back()->withInput()
                ->with('error', 'Maaf, event sudah penuh!');
        }

        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('email', $request->email)
            ->first();

        if ($existingRegistration) {
            return back()->withInput()
                ->with('error', 'Email ini sudah terdaftar untuk event ini!');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'experience_level' => 'required|in:beginner,intermediate,advanced',
            'motivation' => 'required|string|max:1000',
            'special_needs' => 'nullable|string|max:500'
        ];

        if (!$event->is_free) {
            $rules['payment_method'] = 'required|in:' . implode(',', array_keys(EventRegistration::getActivePaymentMethods()));
            
            // Add payment type validation for events with down payment
            if ($event->has_down_payment) {
                $rules['payment_type'] = 'required|in:full_payment,down_payment';
            }
            
            // Add package validation for Expo events
            if ($event->eventCategory && str_contains(strtolower($event->eventCategory->name), 'expo')) {
                $rules['package_type'] = 'required|in:gold,platinum';
            }
            
            if ($request->payment_method === EventRegistration::PAYMENT_METHOD_BANK_TRANSFER) {
                $rules['bukti_pembayaran'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
            }
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $registrationData = [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'company' => $validated['company'] ?? null,
                'position' => $validated['position'] ?? null,
                'experience_level' => $validated['experience_level'],
                'motivation' => $validated['motivation'],
                'special_needs' => $validated['special_needs'] ?? null,
                'registration_status' => $event->is_free ? 'confirmed' : 'pending',
                'payment_status' => $event->is_free ? 'free' : 'pending',
                'registration_date' => now(),
                'confirmation_code' => EventRegistration::generateConfirmationCode()
            ];

            if (!$event->is_free && isset($validated['payment_method'])) {
                $registrationData['payment_method'] = $validated['payment_method'];
                
                // Determine base price based on package type for Expo events
                $basePrice = $event->price;
                if ($event->eventCategory && str_contains(strtolower($event->eventCategory->name), 'expo') && isset($validated['package_type'])) {
                    $registrationData['package_type'] = $validated['package_type'];
                    $basePrice = $validated['package_type'] === 'gold' ? $event->price_gold : $event->price_platinum;
                }
                
                // Calculate payment amount based on payment type
                $isDownPayment = isset($validated['payment_type']) && $validated['payment_type'] === 'down_payment';
                
                if ($event->has_down_payment && $isDownPayment) {
                    // Down payment calculation
                    if ($event->eventCategory && str_contains(strtolower($event->eventCategory->name), 'expo')) {
                        $paymentAmount = $event->getPackageDownPayment($validated['package_type']);
                    } else {
                        $paymentAmount = $event->getPackageDownPayment('regular');
                    }
                    $registrationData['down_payment_amount'] = $paymentAmount;
                    $registrationData['remaining_amount'] = $basePrice - $paymentAmount;
                    $registrationData['payment_amount'] = $paymentAmount;
                } else {
                    // Full payment
                    $registrationData['payment_amount'] = $basePrice;
                    if ($event->has_down_payment) {
                        $registrationData['down_payment_amount'] = $basePrice;
                        $registrationData['remaining_amount'] = 0;
                    }
                }
                
                $registrationData['invoice_number'] = EventRegistration::generateInvoiceNumber();
            }

            $registration = EventRegistration::create($registrationData);

            if (!$event->is_free) {
                if (empty($validated['payment_method'])) {
                    throw new \Exception('Metode pembayaran harus dipilih untuk event berbayar.');
                }

                $buktiPembayaranPath = null;
                if ($validated['payment_method'] === 'bank_transfer' && $request->hasFile('bukti_pembayaran')) {
                    $file = $request->file('bukti_pembayaran');
                    $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $buktiPembayaranPath = $file->storeAs('bukti_pembayaran', $filename, 'public');
                }

                // Only update bukti_pembayaran and payment status if needed
                if ($validated['payment_method'] === 'bank_transfer' && $buktiPembayaranPath) {
                    $paymentStatus = 'waiting_verification';
                    
                    // If this is a down payment, set to waiting verification for down payment
                    if ($event->has_down_payment && isset($validated['payment_type']) && $validated['payment_type'] === 'down_payment') {
                        $paymentStatus = 'waiting_verification'; // Will be changed to 'down_payment_paid' after verification
                    }
                    
                    $registration->update([
                        'bukti_pembayaran' => $buktiPembayaranPath,
                        'payment_status' => $paymentStatus,
                        'payment_date' => now()
                    ]);
                }

                // Record activity
                \App\Models\Activity::create([
                    'user_id' => $registration->user_id,
                    'type' => 'registration',
                    'description' => 'Mendaftar event ' . $event->title,
                    'action_url' => route('events.show', $event),
                    'status' => $registration->registration_status,
                    'metadata' => [
                        'event_id' => $event->id,
                        'registration_id' => $registration->id,
                        'payment_status' => $registration->payment_status
                    ]
                ]);

                DB::commit();

                $message = $validated['payment_method'] === 'bank_transfer' 
                    ? 'Bukti pembayaran berhasil diunggah dan sedang diverifikasi.'
                    : 'Silakan selesaikan pembayaran Anda.';
                
                return redirect()->route('payment.show', $registration->invoice_number)
                    ->with('success')
                    ->with('payment_status');
            }

            $event->increment('current_participants');
            
            // Refresh event data to get accurate count
            $event->refresh();
            
            DB::commit();
            
            return redirect()->route('events.register.form', $event)
                ->with('success', 'Pendaftaran berhasil! Kode konfirmasi: ' . $registration->confirmation_code);


        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Registration Error for event ' . $event->id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage());
        }
    }

    public function index(Request $request): View
    {
        $query = Event::query()
            ->where('is_active', true)
            ->orderBy('start_date', 'asc');
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('event_category_id', $request->category);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('price_filter')) {
            if ($request->price_filter === 'free') {
                $query->where('is_free', true);
            } elseif ($request->price_filter === 'paid') {
                $query->where('is_free', false);
            }
        }

        if ($request->filled('date_filter')) {
            $now = now();
            switch ($request->date_filter) {
                case 'upcoming':
                    $query->where('start_date', '>=', $now);
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [
                        $now->startOfWeek(),
                        $now->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('start_date', [
                        $now->startOfMonth(),
                        $now->endOfMonth()
                    ]);
                    break;
            }
        }
        
        $events = $query->paginate(12)->withQueryString();
        $categories = EventCategory::where('is_active', true)->get();

        return view('event.index', compact('events', 'categories'));
    }
}
