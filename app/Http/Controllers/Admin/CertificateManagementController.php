<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateManagementController extends Controller
{
    public function index()
    {
        $registrations = EventRegistration::with(['event', 'user'])
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.certificates.index', compact('registrations'));
    }

    public function upload(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'certificate_file' => 'required|mimes:pdf|max:5120', // max 5MB
            'certificate_number' => 'required|string|unique:event_registrations,certificate_number'
        ]);

        // Store file
        $path = $request->file('certificate_file')->store('certificates');

        // Update registration
        $registration->update([
            'certificate_number' => $request->certificate_number,
            'certificate_file' => $path,
            'certificate_status' => 'issued',
            'certificate_issued_at' => now(),
            'has_certificate' => true,
            'certificate_metadata' => [
                'issued_by' => auth()->user()->name,
                'file_name' => $request->file('certificate_file')->getClientOriginalName(),
                'mime_type' => $request->file('certificate_file')->getMimeType(),
                'size' => $request->file('certificate_file')->getSize()
            ]
        ]);

        // Create activity log
        activity()
            ->performedOn($registration)
            ->causedBy(auth()->user())
            ->withProperties([
                'event' => $registration->event->title,
                'participant' => $registration->name
            ])
            ->log('certificate_issued');

        return back()->with('success', 'Sertifikat berhasil diunggah');
    }

    public function revoke(EventRegistration $registration)
    {
        // Delete file if exists
        if ($registration->certificate_file) {
            Storage::delete($registration->certificate_file);
        }

        // Update registration
        $registration->update([
            'certificate_status' => 'revoked',
            'certificate_file' => null,
            'has_certificate' => false,
            'certificate_metadata' => array_merge(
                $registration->certificate_metadata ?? [], 
                ['revoked_at' => now(), 'revoked_by' => auth()->user()->name]
            )
        ]);

        // Create activity log
        activity()
            ->performedOn($registration)
            ->causedBy(auth()->user())
            ->withProperties([
                'event' => $registration->event->title,
                'participant' => $registration->name
            ])
            ->log('certificate_revoked');

        return back()->with('success', 'Sertifikat berhasil dicabut');
    }

    public function generateNumber()
    {
        do {
            $number = 'CERT-' . strtoupper(Str::random(8));
        } while (EventRegistration::where('certificate_number', $number)->exists());

        return response()->json(['number' => $number]);
    }
}
