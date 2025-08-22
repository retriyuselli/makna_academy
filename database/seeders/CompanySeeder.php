<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'Makna Academy',
            'slug' => 'makna-academy',
            'logo' => null, // Akan diupdate melalui admin panel
            'description' => 'Makna Academy adalah platform edukasi yang berfokus pada pengembangan skill di bidang teknologi dan soft skill. Kami berkomitmen untuk memberikan pembelajaran berkualitas tinggi melalui event-event yang inovatif dan kolaboratif.',
            'short_description' => 'Platform edukasi teknologi dan pengembangan diri',
            'address' => 'Jl. Teratai No. 123',
            'city' => 'Tangerang Selatan',
            'province' => 'Banten',
            'postal_code' => '15310',
            'phone' => '081234567890',
            'email' => 'info@maknaacademy.com',
            'website' => 'https://maknaacademy.com',
            'facebook' => 'https://facebook.com/maknaacademy',
            'instagram' => 'https://instagram.com/maknaacademy',
            'linkedin' => 'https://linkedin.com/company/maknaacademy',
            'youtube' => 'https://youtube.com/c/maknaacademy',
            'vision' => 'Menjadi platform edukasi terkemuka yang memberdayakan individu untuk mencapai potensi terbaik mereka dalam teknologi dan pengembangan diri.',
            'mission' => "- Menyediakan pendidikan berkualitas tinggi yang terjangkau\n- Membangun komunitas pembelajar yang kolaboratif\n- Mendorong inovasi dalam metode pembelajaran\n- Mengembangkan kemitraan strategis dengan industri\n- Memberikan dampak positif pada perkembangan teknologi di Indonesia",
            'is_active' => true,
            'meta_title' => 'Makna Academy - Platform Edukasi Teknologi dan Pengembangan Diri',
            'meta_description' => 'Tingkatkan skill teknologi dan soft skill Anda bersama Makna Academy melalui program pembelajaran yang inovatif dan berpengalaman.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
