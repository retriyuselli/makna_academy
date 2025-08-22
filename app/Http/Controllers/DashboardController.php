<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\EventRegistration;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Get recent activities for the last 7 days
        $recentActivities = Activity::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get event registrations with event details
        $recentEvents = EventRegistration::with(['event', 'event.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get event statistics
        $registeredEvents = EventRegistration::where('user_id', $user->id)->count();
        $certificateCount = EventRegistration::where('user_id', $user->id)
            ->whereNotNull('certificate_issued_at')
            ->count();
        
        // Get payment statistics using EventRegistration
        $pendingPayments = EventRegistration::where('user_id', $user->id)
            ->whereIn('payment_status', ['pending', 'waiting_verification'])
            ->count();
            
        // Get registrations that require remaining payment (DP paid but still have remaining amount)
        $pendingRemainingPayments = EventRegistration::where('user_id', $user->id)
            ->where('payment_status', 'down_payment_paid')
            ->where('remaining_amount', '>', 0)
            ->with(['event'])
            ->get();
            
        $registrations = EventRegistration::with(['event'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'recentActivities',
            'recentEvents',
            'registeredEvents',
            'certificateCount',
            'pendingPayments',
            'pendingRemainingPayments',
            'registrations'
        ));
    }
}
