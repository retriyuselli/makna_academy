<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventCategory;
use Carbon\Carbon;

class DownPaymentEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventCategories = EventCategory::all();
        
        if ($eventCategories->isEmpty()) {
            $this->command->info('No event categories found. Please run EventCategorySeeder first.');
            return;
        }

        $trainingCategory = $eventCategories->where('name', 'Training')->first();
        $weddingExpoCategory = $eventCategories->where('name', 'Wedding Expo')->first();
        $workshopCategory = $eventCategories->where('name', 'Workshop')->first() ?? $eventCategories->first();

        $events = [
            [
                'event_category_id' => $trainingCategory ? $trainingCategory->id : $eventCategories->first()->id,
                'title' => 'Digital Marketing Training dengan Sistem DP',
                'slug' => 'digital-marketing-training-dengan-sistem-dp',
                'description' => 'Pelatihan digital marketing lengkap dengan sistem pembayaran down payment untuk memudahkan peserta. Dapatkan sertifikat dan materi eksklusif.',
                'short_description' => 'Pelatihan digital marketing dengan sistem DP yang fleksibel',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop',
                'location' => 'Jakarta',
                'venue' => 'Gedung Makna Academy, Lantai 3',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'start_date' => Carbon::now()->addWeeks(3),
                'end_date' => Carbon::now()->addWeeks(3)->addDays(2),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'price' => 2500000,
                'is_free' => false,
                'has_down_payment' => true,
                'down_payment_type' => 'percentage',
                'down_payment_percentage' => 30,
                'max_participants' => 50,
                'current_participants' => 12,
                'rating' => 4.8,
                'total_reviews' => 45,
                'is_featured' => true,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['Digital Marketing', 'Training', 'Sertifikat', 'DP'],
                'contact_email' => 'training@maknaacademy.com',
                'contact_phone' => '08123456789',
                'organizer_name' => 'Makna Academy Training Division',
                'requirements' => ['Laptop', 'Basic computer knowledge', 'Internet connection'],
                'benefits' => ['Sertifikat digital marketing', 'Materi eksklusif', 'Konsultasi 3 bulan', 'Template marketing'],
                'schedule' => [
                    'Hari 1: Fundamental Digital Marketing',
                    'Hari 2: Social Media Marketing',
                    'Hari 3: Google Ads & Analytics'
                ],
                'payment_methods' => ['transfer'],
                'payment_instructions' => 'Pembayaran dapat dilakukan dengan sistem DP 30% untuk mempermudah peserta'
            ],
            [
                'event_category_id' => $weddingExpoCategory ? $weddingExpoCategory->id : $eventCategories->first()->id,
                'title' => 'Wedding Expo Premium dengan DP Fleksibel',
                'slug' => 'wedding-expo-premium-dengan-dp-fleksibel',
                'description' => 'Pameran pernikahan terbesar dengan vendor-vendor premium. Tersedia paket Gold dan Platinum dengan sistem down payment yang fleksibel.',
                'short_description' => 'Wedding expo premium dengan paket Gold & Platinum, DP fleksibel',
                'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=600&fit=crop',
                'location' => 'Surabaya',
                'venue' => 'Grand City Convention Center',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'start_date' => Carbon::now()->addWeeks(4),
                'end_date' => Carbon::now()->addWeeks(4)->addDays(2),
                'start_time' => '10:00:00',
                'end_time' => '20:00:00',
                'price' => 0, // Base price not used for Wedding Expo
                'price_gold' => 5000000,
                'price_platinum' => 8500000,
                'is_free' => false,
                'has_down_payment' => true,
                'down_payment_type' => 'amount',
                'down_payment_amount' => 2000000,
                'max_participants' => 200,
                'current_participants' => 78,
                'rating' => 4.9,
                'total_reviews' => 120,
                'is_featured' => true,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['Wedding', 'Expo', 'Premium', 'Gold', 'Platinum', 'DP'],
                'contact_email' => 'wedding@maknaacademy.com',
                'contact_phone' => '08123456790',
                'organizer_name' => 'Wedding Expo Indonesia',
                'requirements' => ['Valid ID', 'Pre-registration'],
                'benefits' => ['Meet premium vendors', 'Exclusive discounts', 'Wedding consultation', 'Gift vouchers'],
                'schedule' => [
                    'Day 1: Vendor showcase & consultation',
                    'Day 2: Fashion show & demo',
                    'Day 3: Package booking & deals'
                ],
                'payment_methods' => ['transfer'],
                'payment_instructions' => 'DP tetap Rp 2.000.000 untuk semua paket. Sisanya dapat dibayar H-7 event'
            ],
            [
                'event_category_id' => $workshopCategory->id,
                'title' => 'UI/UX Design Workshop - Bayar Bertahap',
                'slug' => 'ui-ux-design-workshop-bayar-bertahap',
                'description' => 'Workshop intensif UI/UX Design selama 5 hari dengan mentor berpengalaman. Sistem pembayaran bertahap dengan DP 40% untuk memudahkan peserta.',
                'short_description' => 'Workshop UI/UX 5 hari dengan sistem pembayaran bertahap',
                'image' => 'https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=800&h=600&fit=crop',
                'location' => 'Bandung',
                'venue' => 'Creative Hub Bandung',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'start_date' => Carbon::now()->addWeeks(5),
                'end_date' => Carbon::now()->addWeeks(5)->addDays(4),
                'start_time' => '09:00:00',
                'end_time' => '16:00:00',
                'price' => 3500000,
                'is_free' => false,
                'has_down_payment' => true,
                'down_payment_type' => 'percentage',
                'down_payment_percentage' => 40,
                'max_participants' => 30,
                'current_participants' => 8,
                'rating' => 4.7,
                'total_reviews' => 28,
                'is_featured' => false,
                'is_trending' => true,
                'is_active' => true,
                'tags' => ['UI/UX', 'Design', 'Workshop', 'Figma', 'DP'],
                'contact_email' => 'workshop@maknaacademy.com',
                'contact_phone' => '08123456791',
                'organizer_name' => 'Design Academy Indonesia',
                'requirements' => ['Laptop with design software', 'Basic design knowledge', 'Portfolio (optional)'],
                'benefits' => ['Professional portfolio', 'Industry certificate', 'Figma pro license', 'Job placement assistance'],
                'schedule' => [
                    'Day 1: Design thinking & principles',
                    'Day 2: User research & personas',
                    'Day 3: Wireframing & prototyping',
                    'Day 4: Visual design & systems',
                    'Day 5: Testing & iteration'
                ],
                'payment_methods' => ['transfer'],
                'payment_instructions' => 'DP 40% (Rp 1.400.000) saat registrasi, sisanya 60% dapat dibayar H-3 workshop'
            ]
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Down payment events seeded successfully!');
        $this->command->info('Created ' . count($events) . ' events with down payment system');
    }
}
