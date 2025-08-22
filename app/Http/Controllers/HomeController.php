<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Company;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index(): View
    {
        // Calculate totals for statistics
        $totalEvents = Event::where('is_active', true)->count();
        
        // Hitung total participants dari EventRegistration yang benar-benar terdaftar
        $totalParticipants = EventRegistration::whereHas('event', function($query) {
                $query->where('is_active', true);
            })
            ->whereIn('registration_status', ['confirmed', 'attended'])
            ->count();
            
        $totalCompanies = Company::count();

        // Get categories with event count
        $categories = EventCategory::withCount(['events' => function($query) {
            $query->where('is_active', true)
                  ->where('start_date', '>=', Carbon::now());
        }])->get();

        // Get featured events (maksimal 6 untuk grid 3 kolom)
        $featuredEvents = Event::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->where('start_date', '>=', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        // Get trending events jika featured events kurang dari 6
        if ($featuredEvents->count() < 6) {
            $additionalEvents = Event::with('category')
                ->where('is_active', true)
                ->where('is_trending', true)
                ->where('start_date', '>=', Carbon::now())
                ->whereNotIn('id', $featuredEvents->pluck('id'))
                ->orderBy('current_participants', 'desc')
                ->limit(6 - $featuredEvents->count())
                ->get();
            
            $featuredEvents = $featuredEvents->merge($additionalEvents);
        }

        // Jika masih kurang, ambil event terbaru
        if ($featuredEvents->count() < 6) {
            $latestEvents = Event::with('category')
                ->where('is_active', true)
                ->where('start_date', '>=', Carbon::now())
                ->whereNotIn('id', $featuredEvents->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->limit(6 - $featuredEvents->count())
                ->get();
            
            $featuredEvents = $featuredEvents->merge($latestEvents);
        }

        // Get statistics for hero section
        $totalEvents = Event::where('is_active', true)
            ->where('start_date', '>=', Carbon::now())
            ->count();

        // Hitung total participants dari EventRegistration yang benar-benar terdaftar
        $totalParticipants = EventRegistration::whereHas('event', function($query) {
                $query->where('is_active', true);
            })
            ->whereIn('registration_status', ['confirmed', 'attended'])
            ->count();

        $totalCompanies = Company::where('is_active', true)->count();

        // Get unique cities for location filter
        $cities = Event::where('is_active', true)
            ->where('start_date', '>=', Carbon::now())
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort()
            ->values();

        // Get event categories for category filter
        $categories = \App\Models\EventCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('front.home', compact(
            'featuredEvents',
            'totalEvents',
            'totalParticipants',
            'totalCompanies',
            'categories',
            'cities',
            'categories'
        ));
    }

    /**
     * Handle search functionality
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        $category = $request->get('category');
        $city = $request->get('city');

        // Redirect to events page with search parameters
        return redirect()->route('events.index', [
            'search' => $query,
            'category' => $category,
            'city' => $city
        ]);
    }
}
