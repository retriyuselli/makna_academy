<?php

namespace App\Services;

use App\Models\EventRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    public function generateCertificate(EventRegistration $registration)
    {
        // Generate certificate number if not exists
        if (!$registration->certificate_number) {
            $registration->certificate_number = $this->generateCertificateNumber($registration);
            $registration->save();
        }

        // Generate QR Code
        $verificationUrl = route('certificate.verify', $registration->certificate_number);
        $qrCode = QrCode::format('png')
                       ->size(100)
                       ->generate($verificationUrl);

        // Prepare certificate data
        $data = [
            'name' => $registration->user->name,
            'event_title' => $registration->event->title,
            'certificate_number' => $registration->certificate_number,
            'completion_date' => $registration->completed_at->format('F d, Y'),
            'qr_code' => base64_encode($qrCode),
            'issued_date' => now()->format('F d, Y')
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('certificates.template', $data);
        $pdf->setPaper('a4', 'landscape');
        
        // Generate filename with safe characters
        $safeNumber = str_replace(['/', '\\'], '-', $registration->certificate_number);
        $fileName = 'certificates/' . $safeNumber . '.pdf';
        
        try {
            // Save to storage
            $success = Storage::disk('public')->put($fileName, $pdf->output());
            
            if (!$success) {
                Log::error("Failed to save certificate file: {$fileName}");
                throw new \Exception("Failed to save certificate file");
            }
            
            // Verify file exists
            if (!Storage::disk('public')->exists($fileName)) {
                Log::error("Certificate file not found after saving: {$fileName}");
                throw new \Exception("Certificate file not found after saving");
            }
            
            // Update registration record
            $registration->update([
                'certificate_path' => $fileName,
                'certificate_issued_at' => now()
            ]);
            
            Log::info("Certificate generated successfully", [
                'registration_id' => $registration->id,
                'file_path' => $fileName,
                'file_exists' => Storage::disk('public')->exists($fileName)
            ]);
        } catch (\Exception $e) {
            Log::error("Error generating certificate", [
                'registration_id' => $registration->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
        
        return $fileName;
    }

    private function generateCertificateNumber(EventRegistration $registration)
    {
        $prefix = 'CERT';
        $event_code = substr(strtoupper(Str::slug($registration->event->title)), 0, 3);
        $year = date('Y');
        $random = strtoupper(Str::random(4));
        
        return "{$prefix}-{$event_code}-{$year}-{$random}";
    }
}
