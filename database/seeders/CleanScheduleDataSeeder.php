<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class CleanScheduleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::whereNotNull('schedule')->get();
        
        foreach ($events as $event) {
            $currentSchedule = $event->getRawOriginal('schedule');
            
            // Jika schedule masih berupa HTML string, bersihkan
            if (is_string($currentSchedule) && (strpos($currentSchedule, '<p>') !== false || strpos($currentSchedule, '<') !== false)) {
                $this->command->info("Cleaning schedule for Event: {$event->title}");
                
                // Set default schedule berdasarkan kategori event
                $defaultSchedule = $this->getDefaultScheduleByCategory($event);
                
                $event->schedule = $defaultSchedule;
                $event->save();
                
                $this->command->info("Updated schedule: " . json_encode($defaultSchedule));
            } else {
                $this->command->info("Event {$event->title} - Schedule already in correct format");
            }
        }
        
        $this->command->info('Schedule data cleanup completed!');
    }
    
    private function getDefaultScheduleByCategory($event): array
    {
        $categoryName = $event->eventCategory ? $event->eventCategory->name : 'default';
        
        switch ($categoryName) {
            case 'Wedding Expo':
            case 'Property Expo':
            case 'Travel Expo':
            case str_contains(strtolower($categoryName), 'expo'):
                return [
                    'Hari 1: Registrasi dan Pembukaan',
                    'Hari 2: Pameran Vendor Premium',
                    'Hari 3: Fashion Show dan Penutupan'
                ];
            case 'Seminar':
            case 'Workshop':
                return [
                    'Registrasi Peserta (08:00 - 09:00)',
                    'Sesi 1: Materi Fundamental (09:00 - 10:30)',
                    'Coffee Break (10:30 - 11:00)',
                    'Sesi 2: Praktik dan Diskusi (11:00 - 12:30)',
                    'Penutupan dan Evaluasi (12:30 - 13:00)'
                ];
            case 'Conference':
                return [
                    'Registrasi dan Welcome Coffee (08:00 - 09:00)',
                    'Keynote Speaker (09:00 - 10:00)',
                    'Panel Discussion (10:00 - 11:30)',
                    'Networking Session (11:30 - 12:00)',
                    'Closing Remarks (12:00 - 12:30)'
                ];
            default:
                return [
                    'Pembukaan Event',
                    'Sesi Utama',
                    'Penutupan'
                ];
        }
    }
}
