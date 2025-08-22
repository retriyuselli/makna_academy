<?php

namespace App\Console\Commands;

use App\Models\EventRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateCertificates extends Command
{
    protected $signature = 'certificates:generate';
    protected $description = 'Generate certificates for eligible event registrations';

    public function handle()
    {
        $eligibleRegistrations = EventRegistration::whereNull('certificate_number')
            ->whereNull('certificate_path')
            ->whereNull('certificate_issued_at')
            ->where('payment_status', 'paid')
            ->where('is_attended', true)
            ->whereHas('event', function ($query) {
                $query->where('end_date', '<', now());
            })
            ->get();

        $count = 0;
        foreach ($eligibleRegistrations as $registration) {
            $certificateNumber = 'CERT/' . date('Y') . '/' . 
                               strtoupper(substr($registration->event->title ?? 'EVENT', 0, 3)) . '/' . 
                               str_pad($registration->id, 5, '0', STR_PAD_LEFT);

            // Generate PDF from template
            $pdf = Pdf::loadView('certificates.template', [
                'name' => $registration->name,
                'event_title' => $registration->event->title,
                'completion_date' => $registration->completed_at->format('d F Y'),
                'certificate_number' => $certificateNumber,
                'qr_code' => QrCode::format('png')
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate(route('certificates.verify', $certificateNumber)),
                'issued_date' => now()->format('d F Y')
            ]);

            // Save PDF to storage
            $pdfPath = 'certificates/' . $certificateNumber . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdf->output());

            $registration->update([
                'certificate_number' => $certificateNumber,
                'certificate_path' => $pdfPath,
                'certificate_issued_at' => now(),
                'certificate_status' => 'issued'
            ]);

            $count++;
        }

        $this->info("Generated {$count} certificates.");
    }
}
