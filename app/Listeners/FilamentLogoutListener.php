<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FilamentLogoutListener
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        // Check if this is a logout from Filament admin panel
        if (request()->is('admin/*') || request()->routeIs('filament.admin.*')) {
            // Store a session flag to redirect to home after logout
            session()->flash('redirect_to_home', true);
        }
    }
}
