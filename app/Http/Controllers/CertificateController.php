<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateController extends Controller
{
    /**
     * Display a listing of user certificates
     */
    public function index(): View
    {
        $certificates = EventRegistration::with(['event', 'event.category'])
            ->where('user_id', Auth::id())
            ->whereNotNull('certificate_issued_at')
            ->orderBy('certificate_issued_at', 'desc')
            ->paginate(10);

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Display the specified certificate
     */
    public function show(EventRegistration $certificate): View
    {
        // Ensure the certificate belongs to the authenticated user
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the certificate has been issued
        if (!$certificate->certificate_issued_at) {
            abort(404, 'Certificate not found.');
        }

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Preview certificate template
     */
    public function previewTemplate(EventRegistration $certificate): View
    {
        // Ensure the certificate belongs to the authenticated user
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load necessary relationships
        $certificate->load(['event', 'user']);

        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->errorCorrection('H')
            ->generate(route('certificate.verify', ['number' => $certificate->certificate_number]));

        // Convert QR code to base64 for inline display
        $qrCodeBase64 = base64_encode($qrCode);

        return view('certificates.template', [
            'certificate' => $certificate,
            'qrCode' => $qrCodeBase64,
        ]);
    }

    /**
     * Download the certificate file
     */
    public function stream(EventRegistration $certificate)
    {
        Log::info('Attempting to stream certificate', [
            'registration_id' => $certificate->id,
            'user_id' => $certificate->user_id,
            'auth_id' => Auth::id()
        ]);

        // Ensure the certificate belongs to the authenticated user or is being verified
        if ($certificate->user_id !== Auth::id() && !request()->has('verify')) {
            abort(403, 'Unauthorized action.');
        }

        // Generate certificate
        try {
            $pdf = Pdf::loadView('certificates.template', [
                'certificate' => $certificate,
                'qrCode' => QrCode::size(100)->generate(
                    route('certificate.verify', ['number' => $certificate->certificate_number])
                )
            ]);

            // Set PDF properties
            $pdf->setPaper('A4', 'landscape');
            
            // Stream the PDF
            return $pdf->stream("certificate-{$certificate->certificate_number}.pdf");
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate: ' . $e->getMessage(), [
                'certificate' => $certificate->id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Failed to generate certificate.');
        }

        // Ensure the certificate has been issued and has a file
        if (!$certificate->certificate_issued_at || !$certificate->certificate_path) {
            abort(404, 'Certificate file not found.');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($certificate->certificate_path)) {
            abort(404, 'Certificate file not found.');
        }

        // Stream the file inline
        return response()->file(
            storage_path('app/public/' . $certificate->certificate_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    public function download(EventRegistration $certificate)
    {
        // Ensure the certificate belongs to the authenticated user
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the certificate has been issued
        if (!$certificate->certificate_issued_at) {
            abort(404, 'Certificate not found or not yet issued.');
        }

        try {
            // Load necessary relationships
            $certificate->load(['event', 'event.category', 'user']);

            // Generate QR code
            $qrCode = QrCode::format('png')
                ->size(200)
                ->margin(1)
                ->errorCorrection('H')
                ->generate(route('certificate.verify', ['number' => $certificate->certificate_number]));

            // Generate PDF menggunakan template yang sama dengan preview
            $pdf = Pdf::loadView('certificates.template', [
                'certificate' => $certificate,
                'qrCode' => base64_encode($qrCode)
            ]);

            // Configure PDF
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setOption('images', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);

            // Clean the certificate number for filename
            $safeFileName = preg_replace('/[^a-zA-Z0-9]/', '-', $certificate->certificate_number);
            
            // Return the PDF for download
            return $pdf->download("sertifikat-" . $safeFileName . ".pdf");
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate PDF: ' . $e->getMessage(), [
                'certificate_id' => $certificate->id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Gagal mengunduh sertifikat.');
        }
    }
    
    /**
     * Verify a certificate by its number
     */
    public function verify(string $number): View
    {
        $registration = EventRegistration::where('certificate_number', $number)
            ->whereNotNull('certificate_issued_at')
            ->with(['event', 'user'])
            ->firstOrFail();
            
        return view('certificates.verify', compact('registration'));
    }
}
