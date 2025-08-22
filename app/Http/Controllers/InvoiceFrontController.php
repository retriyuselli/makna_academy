<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceFrontController extends Controller
{
    /**
     * Show invoice in browser with download/print functionality
     */
    public function show(string $invoice)
    {
        // Find the registration by invoice number
        $registration = EventRegistration::with(['event', 'user'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        // Check if user can access this invoice
        if (Auth::id() !== $registration->user_id) {
            abort(403, 'Unauthorized access to invoice');
        }

        // Prepare data for invoice
        $data = [
            'registration' => $registration,
            'event' => $registration->event,
            'user' => $registration->user,
            'company' => $registration->event->company,
            'generated_at' => Carbon::now(),
            'is_down_payment' => $registration->payment_amount < $registration->event->price,
        ];

        return view('invoices.template', $data);
    }

    /**
     * Download invoice for event registration (PDF)
     */
    public function download(string $invoice)
    {
        // Find the registration by invoice number
        $registration = EventRegistration::with(['event', 'user'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        // Check if user can access this invoice
        if (Auth::id() !== $registration->user_id) {
            abort(403, 'Unauthorized access to invoice');
        }

        // Prepare data for invoice
        $data = [
            'registration' => $registration,
            'event' => $registration->event,
            'user' => $registration->user,
            'company' => $registration->event->company,
            'generated_at' => Carbon::now(),
            'is_down_payment' => $registration->payment_amount < $registration->event->price,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('invoices.template', $data);
        $pdf->setPaper('A4', 'portrait');

        // Download the PDF
        $filename = "Invoice-{$registration->invoice_number}.pdf";
        
        return $pdf->stream($filename);
    }
}
