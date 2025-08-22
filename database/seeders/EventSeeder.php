<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil kategori yang sudah ada (berdasarkan kategori terbaru)
        $weddingExpo = EventCategory::where('name', 'Wedding Expo')->first();
        $seminar = EventCategory::where('name', 'Seminar')->first();
        $workshop = EventCategory::where('name', 'Workshop')->first();
        $conference = EventCategory::where('name', 'Conference')->first();
        $propertyExpo = EventCategory::where('name', 'Property Expo')->first();
        $charityEvent = EventCategory::where('name', 'Charity Event')->first();
        $festival = EventCategory::where('name', 'Festival')->first();
        $exhibition = EventCategory::where('name', 'Exhibition')->first();
        $sportEvent = EventCategory::where('name', 'Sport Event')->first();
        $familyGathering = EventCategory::where('name', 'Family Gathering')->first();
        $productLaunch = EventCategory::where('name', 'Product Launch')->first();
        $religiousEvent = EventCategory::where('name', 'Religious Event')->first();
        $communityEvent = EventCategory::where('name', 'Community Event')->first();
        $educationFair = EventCategory::where('name', 'Education Fair')->first();
        $jobFair = EventCategory::where('name', 'Job Fair')->first();
        $travelExpo = EventCategory::where('name', 'Travel Expo')->first();

    $events = [
            [
                'event_category_id' => $weddingExpo?->id,
                'title' => 'Makna Wedding Expo Palembang 2025',
                'slug' => Str::slug('Makna Wedding Expo Palembang 2025'),
                'description' => 'Pameran wedding terbesar di Palembang tahun ini, hadirkan vendor-vendor terbaik.',
                'short_description' => 'Expo vendor wedding & inspirasi pernikahan di Palembang.',
                'image' => null,
                'location' => 'Palembang Convention Center',
                'venue' => 'Hall A',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(12),
                'start_time' => '10:00',
                'end_time' => '20:00',
                'price' => 0,
                'price_gold' => 500000,
                'price_platinum' => 1000000,
                'is_free' => false,
                'max_participants' => 2000,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => true,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['wedding','expo','vendor','palembang'],
                'contact_email' => 'info@maknaexpo.com',
                'contact_phone' => '0711234567',
                'organizer_name' => 'Makna Organizer Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
            [
                'event_category_id' => $seminar?->id,
                'title' => 'Seminar Digital Marketing Palembang',
                'slug' => Str::slug('Seminar Digital Marketing Palembang'),
                'description' => 'Seminar tentang strategi digital marketing untuk UMKM di era modern.',
                'short_description' => 'Seminar digital marketing untuk UMKM.',
                'image' => null,
                'location' => 'Hotel Zuri Palembang',
                'venue' => 'Grand Ballroom',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(20),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'price' => 250000,
                'is_free' => false,
                'max_participants' => 500,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => false,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['seminar','digital','marketing','palembang'],
                'contact_email' => 'seminar@maknaacademy.com',
                'contact_phone' => '0711234568',
                'organizer_name' => 'Makna Academy Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
            [
                'event_category_id' => $workshop?->id,
                'title' => 'Workshop Fotografi Palembang',
                'slug' => Str::slug('Workshop Fotografi Palembang'),
                'description' => 'Workshop fotografi untuk pemula hingga profesional di Palembang.',
                'short_description' => 'Workshop fotografi dari basic hingga advanced.',
                'image' => null,
                'location' => 'Studio Foto Palembang',
                'venue' => 'Main Studio',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(31),
                'start_time' => '10:00',
                'end_time' => '16:00',
                'price' => 350000,
                'is_free' => false,
                'max_participants' => 50,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => false,
                'is_trending' => false,
                'is_active' => true,
                'tags' => ['workshop','fotografi','palembang'],
                'contact_email' => 'workshop@maknaacademy.com',
                'contact_phone' => '0711234569',
                'organizer_name' => 'Makna Creative Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
            [
                'event_category_id' => $conference?->id,
                'title' => 'Conference Tech Startup Palembang',
                'slug' => Str::slug('Conference Tech Startup Palembang'),
                'description' => 'Konferensi teknologi dan startup untuk pengembangan ekosistem digital Palembang.',
                'short_description' => 'Konferensi teknologi dan startup.',
                'image' => null,
                'location' => 'Universitas Sriwijaya Palembang',
                'venue' => 'Auditorium Utama',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(15),
                'start_time' => '08:00',
                'end_time' => '18:00',
                'price' => 150000,
                'is_free' => false,
                'max_participants' => 300,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => true,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['conference','tech','startup','palembang'],
                'contact_email' => 'conference@maknatech.com',
                'contact_phone' => '0711234570',
                'organizer_name' => 'Makna Tech Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
            [
                'event_category_id' => $propertyExpo?->id,
                'title' => 'Property Expo Palembang 2025',
                'slug' => Str::slug('Property Expo Palembang 2025'),
                'description' => 'Pameran properti terbesar di Palembang dengan penawaran menarik.',
                'short_description' => 'Expo properti dengan penawaran terbaik.',
                'image' => null,
                'location' => 'Palembang Square',
                'venue' => 'Exhibition Hall',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(40),
                'end_date' => now()->addDays(42),
                'start_time' => '10:00',
                'end_time' => '21:00',
                'price' => 0,
                'is_free' => true,
                'max_participants' => 1500,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => true,
                'is_trending' => false,
                'is_active' => true,
                'tags' => ['property','expo','palembang'],
                'contact_email' => 'property@maknaexpo.com',
                'contact_phone' => '0711234571',
                'organizer_name' => 'Makna Property Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
            [
                'event_category_id' => $charityEvent?->id,
                'title' => 'Charity Event Peduli Palembang',
                'slug' => Str::slug('Charity Event Peduli Palembang'),
                'description' => 'Acara amal untuk membantu masyarakat kurang mampu di Palembang.',
                'short_description' => 'Acara amal peduli sesama.',
                'image' => null,
                'location' => 'Masjid Agung Palembang',
                'venue' => 'Halaman Masjid',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(25),
                'end_date' => now()->addDays(25),
                'start_time' => '07:00',
                'end_time' => '12:00',
                'price' => 0,
                'is_free' => true,
                'max_participants' => 1000,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => false,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['charity','amal','palembang'],
                'contact_email' => 'charity@maknacare.com',
                'contact_phone' => '0711234572',
                'organizer_name' => 'Makna Care Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
            [
                'event_category_id' => $festival?->id,
                'title' => 'Festival Kuliner Palembang',
                'slug' => Str::slug('Festival Kuliner Palembang'),
                'description' => 'Festival kuliner khas Palembang dan Sumatera Selatan.',
                'short_description' => 'Festival kuliner khas Palembang.',
                'image' => null,
                'location' => 'Benteng Kuto Besak',
                'venue' => 'Area Terbuka',
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(35),
                'end_date' => now()->addDays(37),
                'start_time' => '16:00',
                'end_time' => '23:00',
                'price' => 25000,
                'is_free' => false,
                'max_participants' => 2500,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => true,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['festival','kuliner','palembang'],
                'contact_email' => 'festival@maknaculinary.com',
                'contact_phone' => '0711234573',
                'organizer_name' => 'Makna Culinary Palembang',
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate([
                'title' => $event['title'],
            ], $event);
        }

        // Tambahkan event dummy agar total menjadi 20 event
        $count = count($events);
        $categories = [$weddingExpo, $seminar, $workshop, $conference, $propertyExpo, $charityEvent, $festival, $exhibition, $sportEvent, $familyGathering, $productLaunch, $religiousEvent, $communityEvent, $educationFair, $jobFair, $travelExpo];
        
        $eventTitles = [
            'Exhibition Seni Budaya Palembang',
            'Sport Event Marathon Palembang',
            'Family Gathering Reuni Akbar',
            'Product Launch Smartphone Terbaru',
            'Religious Event Pengajian Akbar',
            'Community Event Gotong Royong',
            'Education Fair Universitas Palembang',
            'Job Fair Karir Palembang',
            'Travel Expo Wisata Sumsel',
            'Festival Musik Tradisional',
            'Workshop Kerajinan Tangan',
            'Seminar Ekonomi Digital',
            'Conference Teknologi AI'
        ];
        
        $venues = [
            'Gedung Serbaguna Palembang',
            'Stadion Gelora Sriwijaya',
            'Taman Kambang Iwak',
            'Mall Palembang Icon',
            'Masjid Cheng Hoo',
            'Balai Kota Palembang',
            'Kampus UNSRI',
            'Hotel Santika Palembang',
            'Benteng Kuto Besak',
            'Pulau Kemaro',
            'Museum Sultan Mahmud Badaruddin II',
            'BEC (Bumi Sriwijaya Convention Center)',
            'OPI Mall Palembang'
        ];
        
        for ($i = $count; $i < 20; $i++) {
            $cat = $categories[array_rand($categories)];
            $index = $i - $count;
            $title = $eventTitles[$index] ?? "Event Palembang " . ($i + 1);
            $venue = $venues[array_rand($venues)];
            
            $event = [
                'event_category_id' => $cat?->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => "Deskripsi lengkap untuk event $title yang akan diselenggarakan di Palembang.",
                'short_description' => "Event menarik di Palembang.",
                'image' => null,
                'location' => $venue,
                'venue' => $venue,
                'city' => 'Palembang',
                'province' => 'Sumatera Selatan',
                'start_date' => now()->addDays(10 + $i),
                'end_date' => now()->addDays(11 + $i),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'price' => ($i % 2 == 0) ? 0 : (50000 + ($i * 25000)),
                'price_gold' => ($cat?->name === 'Wedding Expo') ? (300000 + ($i * 50000)) : null,
                'price_platinum' => ($cat?->name === 'Wedding Expo') ? (600000 + ($i * 100000)) : null,
                'is_free' => ($i % 2 == 0),
                'max_participants' => 100 + $i * 10,
                'current_participants' => 0,
                'rating' => 0,
                'total_reviews' => 0,
                'is_featured' => ($i % 3 == 0),
                'is_trending' => ($i % 2 == 1),
                'is_active' => true,
                'tags' => ['palembang','event','sumsel'],
                'contact_email' => "event" . ($i + 1) . "@maknaacademy.com",
                'contact_phone' => '0711234' . str_pad($i + 574, 3, '0', STR_PAD_LEFT),
                'organizer_name' => "Makna Event Palembang " . ($i + 1),
                'requirements' => null,
                'benefits' => null,
                'schedule' => null,
            ];
            Event::firstOrCreate([
                'title' => $event['title'],
            ], $event);
        }
    }
}
