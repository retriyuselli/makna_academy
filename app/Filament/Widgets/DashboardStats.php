<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $users = User::count();
        $events = Event::count();
        $upcoming = Event::active()->upcoming()->count();
        $waiting = EventRegistration::where('payment_status', EventRegistration::PAYMENT_STATUS_WAITING_VERIFICATION)->count();
        $paid = EventRegistration::where('payment_status', EventRegistration::PAYMENT_STATUS_FULLY_PAID)->count();
        $fullyRevenue = (float) EventRegistration::where('payment_status', EventRegistration::PAYMENT_STATUS_FULLY_PAID)->sum('payment_amount');
        $dpRevenue = (float) EventRegistration::where('payment_status', EventRegistration::PAYMENT_STATUS_DOWN_PAYMENT_PAID)->sum('down_payment_amount');
        $revenue = $fullyRevenue + $dpRevenue;

        return [
            Stat::make('Users', $users),
            Stat::make('Events', $events),
            Stat::make('Upcoming Events', $upcoming),
            Stat::make('Payments Waiting Verification', $waiting),
            Stat::make('Fully Paid Registrations', $paid),
            Stat::make('Revenue Received', 'Rp ' . number_format($revenue, 0, ',', '.')),
        ];
    }
}
