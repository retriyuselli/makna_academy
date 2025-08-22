<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Display a listing of user's registrations
     */
    public function index(): View
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('event.registrations.index', compact('registrations'));
    }

    /**
     * Display the specified registration
     */
    public function show(EventRegistration $registration): View
    {
        // Check if user owns this registration
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('event.registrations.show', compact('registration'));
    }

    /**
     * Cancel a registration
     */
    public function cancel(EventRegistration $registration): JsonResponse
    {
        // Check if user owns this registration
        if ($registration->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if cancellation is allowed (e.g., not within 24 hours of event)
        $event = $registration->event;
        $hoursUntilEvent = now()->diffInHours($event->start_date);
        
        if ($hoursUntilEvent < 24) {
            return response()->json(['error' => 'Tidak dapat membatalkan pendaftaran kurang dari 24 jam sebelum event'], 400);
        }

        DB::beginTransaction();
        try {
            // Update registration status
            $registration->update([
                'registration_status' => 'cancelled'
            ]);

            // Decrease participant count
            $event->decrement('current_participants');

            DB::commit();

            return response()->json(['message' => 'Pendaftaran berhasil dibatalkan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat membatalkan pendaftaran'], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePayment(Request $request, EventRegistration $registration): JsonResponse
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Check if user owns this registration
        if ($registration->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('payment_proofs', $filename, 'public');

                $registration->update([
                    'payment_status' => 'pending_verification',
                    'payment_proof' => $filename
                ]);
            }

            return response()->json(['message' => 'Bukti pembayaran berhasil diupload']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengupload bukti pembayaran'], 500);
        }
    }
}
