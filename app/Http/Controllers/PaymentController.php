<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(): View
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('payment.index', compact('registrations'));
    }

    public function show(string $invoice): View
    {
        // Find registration by invoice number
        $registration = EventRegistration::where('invoice_number', $invoice)
            ->with(['event', 'user'])
            ->firstOrFail();
        
        // Check if user has access to this registration
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('payment.show', [
            'registration' => $registration
        ]);
    }
}
