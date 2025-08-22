<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Event;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the about page with company information
     */
    public function index(): View
    {
        // Get the first active company (assuming single company setup)
        $company = Company::where('is_active', true)->first();
        
        // Calculate total events for statistics
        $totalEvents = Event::where('is_active', true)->count();
        
        // If no company found, create default data
        if (!$company) {
            $company = (object) [
                'name' => 'Makna Academy',
                'description' => 'Platform terpercaya untuk menemukan workshop, seminar, dan pelatihan berkualitas tinggi.',
                'email' => 'info@maknaacademy.com',
                'phone' => '+62 21 1234 5678',
                'address' => 'Jl. Pendidikan No. 123, Jakarta Selatan',
                'website' => 'https://maknaacademy.com',
                'established_date' => '2020-01-01',
                'social_media' => [
                    'facebook' => 'maknaacademy',
                    'instagram' => 'maknaacademy',
                    'twitter' => 'maknaacademy',
                    'linkedin' => 'maknaacademy'
                ]
            ];
        }
        
        return view('front.about', compact('company', 'totalEvents'));
    }
}
