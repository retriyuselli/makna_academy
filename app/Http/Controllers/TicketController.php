<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function download(EventRegistration $registration)
    {
        // Check if the registration is fully paid or free
        if (!in_array($registration->payment_status, ['fully_paid', 'free'])) {
            abort(403, 'Ticket hanya tersedia untuk registrasi yang sudah dibayar lunas.');
        }

        // Load relationships
        $registration->load(['event', 'user']);

        // Generate PDF ticket
        $pdf = Pdf::loadView('tickets.template', [
            'registration' => $registration,
            'event' => $registration->event,
            'user' => $registration->user,
        ]);

        $filename = 'ticket-' . $registration->event->slug . '-' . $registration->user->name . '.pdf';

        return $pdf->download($filename);
    }
}
