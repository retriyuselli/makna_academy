<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class UpdateCurrentParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        
        foreach ($events as $event) {
            $actualParticipants = $event->registrations()
                ->whereNotIn('registration_status', ['cancelled'])
                ->count();
                
            $event->update(['current_participants' => $actualParticipants]);
            
            $this->command->info("Updated Event: {$event->title} - Participants: {$actualParticipants}");
        }
        
        $this->command->info('All events participant counts have been updated successfully!');
    }
}
