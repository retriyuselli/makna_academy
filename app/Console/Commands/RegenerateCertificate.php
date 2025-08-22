<?php

namespace App\Console\Commands;

use App\Models\EventRegistration;
use App\Services\CertificateService;
use Illuminate\Console\Command;

class RegenerateCertificate extends Command
{
    protected $signature = 'certificate:regenerate {registration_id}';
    protected $description = 'Regenerate certificate for a specific event registration';

    public function handle(CertificateService $certificateService)
    {
        $registration = EventRegistration::findOrFail($this->argument('registration_id'));
        
        $this->info("Regenerating certificate for registration #{$registration->id}...");
        
        try {
            $path = $certificateService->generateCertificate($registration);
            $this->info("Certificate generated successfully at: {$path}");
        } catch (\Exception $e) {
            $this->error("Failed to generate certificate: " . $e->getMessage());
        }
    }
}
