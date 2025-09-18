<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\Materi;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display the learning materials page
     * Shows downloadable materials for events the user has completed
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all completed event registrations for the authenticated user
        // A registration is considered "completed" when:
        // 1. Registration is confirmed
        // 2. Payment is completed (fully_paid or free)
        $completedRegistrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->where('registration_status', 'confirmed')
            ->whereIn('payment_status', [
                EventRegistration::PAYMENT_STATUS_FULLY_PAID,
                EventRegistration::PAYMENT_STATUS_FREE
            ])
            ->get();

        // Extract completed event IDs
        $completedEventIds = $completedRegistrations->pluck('event_id')->unique();
        
        // Get events with their materials for completed events
        $eventsWithMaterials = Event::with(['materials' => function($query) use ($user) {
                // Get materials that are accessible by the user
                $query->active()
                      ->accessibleBy($user, true) // true = has completed event
                      ->orderBy('category')
                      ->orderBy('sort_order');
            }])
            ->whereIn('id', $completedEventIds)
            ->orderBy('start_date', 'desc')
            ->get();

        // Filter out events that have no accessible materials
        $eventsWithMaterials = $eventsWithMaterials->filter(function($event) {
            return $event->materials->count() > 0;
        });

        return view('materi.materi', compact('eventsWithMaterials'));
    }
    
    /**
     * Download learning material file
     * Only allow downloads for users who completed the event
     */
    public function download(Request $request, $materiId)
    {
        $user = Auth::user();
        
        // Find the material
        $materi = Materi::with('event')->findOrFail($materiId);
        
        // Check if user has completed the event for this material
        $hasCompletedEvent = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $materi->event_id)
            ->where('registration_status', 'confirmed')
            ->whereIn('payment_status', [
                EventRegistration::PAYMENT_STATUS_FULLY_PAID,
                EventRegistration::PAYMENT_STATUS_FREE
            ])
            ->exists();
            
        // Check if material is accessible
        if (!$materi->isAccessibleBy($user, $hasCompletedEvent)) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh materi ini.');
        }
        
        // Check if file exists
        if (!Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        // Increment download count
        $materi->incrementDownloadCount();
        
        // Get file path and return download response
        $filePath = Storage::disk('public')->path($materi->file_path);
        return response()->download($filePath, $materi->file_name);
    }
}
