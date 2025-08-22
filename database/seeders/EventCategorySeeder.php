<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventCategory;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $categories = [
            [ 'name' => 'Wedding Expo', 'description' => 'Kategori untuk pameran pernikahan dan vendor terkait.' ],
            [ 'name' => 'Expo', 'description' => 'Kategori untuk pameran dan expo terkait.' ],
            [ 'name' => 'Seminar', 'description' => 'Kategori untuk seminar dan pelatihan.' ],
            [ 'name' => 'Workshop', 'description' => 'Kategori untuk workshop dan pelatihan praktis.' ],
            [ 'name' => 'Conference', 'description' => 'Kategori untuk konferensi dan simposium.' ],
            [ 'name' => 'Property Expo', 'description' => 'Kategori untuk pameran properti dan real estate.' ],
            [ 'name' => 'Charity Event', 'description' => 'Kategori untuk acara amal dan penggalangan dana.' ],
            [ 'name' => 'Festival', 'description' => 'Kategori untuk festival budaya, kuliner, dan seni.' ],
            [ 'name' => 'Exhibition', 'description' => 'Kategori untuk pameran produk, seni, dan lainnya.' ],
            [ 'name' => 'Sport Event', 'description' => 'Kategori untuk event olahraga dan kompetisi.' ],
            [ 'name' => 'Family Gathering', 'description' => 'Kategori untuk acara keluarga dan reuni.' ],
            [ 'name' => 'Product Launch', 'description' => 'Kategori untuk peluncuran produk baru.' ],
            [ 'name' => 'Religious Event', 'description' => 'Kategori untuk acara keagamaan.' ],
            [ 'name' => 'Community Event', 'description' => 'Kategori untuk acara komunitas dan sosial.' ],
            [ 'name' => 'Education Fair', 'description' => 'Kategori untuk pameran pendidikan dan kampus.' ],
            [ 'name' => 'Job Fair', 'description' => 'Kategori untuk bursa kerja dan rekrutmen.' ],
            [ 'name' => 'Travel Expo', 'description' => 'Kategori untuk pameran wisata dan travel.' ],
            [ 'name' => 'Lain - lain', 'description' => 'Kategori untuk event lainnya yang tidak terdaftar.' ],
        ];

        foreach ($categories as $cat) {
            EventCategory::firstOrCreate([
                'name' => $cat['name'],
            ], [
                'description' => $cat['description'],
            ]);
        }
    }
}
