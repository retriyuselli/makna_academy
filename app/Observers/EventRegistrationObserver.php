<?php

namespace App\Observers;

use App\Models\EventRegistration;
use App\Models\Activity;

class EventRegistrationObserver
{
    /**
     * Handle the EventRegistration "created" event.
     */
    public function created(EventRegistration $eventRegistration): void
    {
        // Update current participants count
        $this->updateParticipantCount($eventRegistration->event_id);

        Activity::create([
            'user_id' => $eventRegistration->user_id,
            'type' => 'registration',
            'description' => 'Mendaftar event ' . $eventRegistration->event->title,
            'action_url' => route('events.show', $eventRegistration->event),
            'status' => $eventRegistration->registration_status,
            'metadata' => [
                'event_id' => $eventRegistration->event_id,
                'registration_id' => $eventRegistration->id,
                'payment_status' => $eventRegistration->payment_status
            ]
        ]);
    }

    /**
     * Handle the EventRegistration "updated" event.
     */
    public function updated(EventRegistration $eventRegistration): void
    {
        // Update participant count if registration status changed
        if ($eventRegistration->wasChanged('registration_status')) {
            $this->updateParticipantCount($eventRegistration->event_id);
        }

        // Jika status pembayaran berubah menjadi 'paid'
        if ($eventRegistration->wasChanged('payment_status') && $eventRegistration->payment_status === 'paid') {
            Activity::create([
                'user_id' => $eventRegistration->user_id,
                'type' => 'payment',
                'description' => 'Pembayaran berhasil untuk event ' . $eventRegistration->event->title,
                'action_url' => route('events.show', $eventRegistration->event),
                'status' => 'success',
                'metadata' => [
                    'event_id' => $eventRegistration->event_id,
                    'registration_id' => $eventRegistration->id,
                    'payment_amount' => $eventRegistration->event->price
                ]
            ]);
        }

        // Jika event selesai (completion_date diisi)
        if ($eventRegistration->wasChanged('completion_date') && $eventRegistration->completion_date) {
            Activity::create([
                'user_id' => $eventRegistration->user_id,
                'type' => 'completion',
                'description' => 'Menyelesaikan event ' . $eventRegistration->event->title,
                'action_url' => route('events.show', $eventRegistration->event),
                'status' => 'completed',
                'metadata' => [
                    'event_id' => $eventRegistration->event_id,
                    'registration_id' => $eventRegistration->id,
                    'completion_date' => $eventRegistration->completion_date
                ]
            ]);
        }

        // Jika sertifikat diterbitkan
        if ($eventRegistration->wasChanged('certificate_issued_at') && $eventRegistration->certificate_issued_at) {
            Activity::create([
                'user_id' => $eventRegistration->user_id,
                'type' => 'certificate',
                'description' => 'Menerima sertifikat untuk event ' . $eventRegistration->event->title,
                'action_url' => route('certificates.show', ['certificate' => $eventRegistration->id]),
                'status' => 'issued',
                'metadata' => [
                    'event_id' => $eventRegistration->event_id,
                    'registration_id' => $eventRegistration->id,
                    'certificate_issued_at' => $eventRegistration->certificate_issued_at
                ]
            ]);
        }
    }

    /**
     * Handle the EventRegistration "deleted" event.
     */
    public function deleted(EventRegistration $eventRegistration): void
    {
        // Update participant count when registration is deleted
        $this->updateParticipantCount($eventRegistration->event_id);
    }

    /**
     * Handle the EventRegistration "restored" event.
     */
    public function restored(EventRegistration $eventRegistration): void
    {
        // Update participant count when registration is restored
        $this->updateParticipantCount($eventRegistration->event_id);
    }

    /**
     * Handle the EventRegistration "force deleted" event.
     */
    public function forceDeleted(EventRegistration $eventRegistration): void
    {
        // Update participant count when registration is force deleted
        $this->updateParticipantCount($eventRegistration->event_id);
    }

    /**
     * Update current participants count for an event
     */
    private function updateParticipantCount(int $eventId): void
    {
        $event = \App\Models\Event::find($eventId);
        if ($event) {
            $actualParticipants = $event->registrations()
                ->whereNotIn('registration_status', ['cancelled'])
                ->count();
            
            $event->update(['current_participants' => $actualParticipants]);
        }
    }
}
