<?php

namespace App\Providers;

use App\Models\EventRegistration;
use App\Observers\EventRegistrationObserver;
use App\View\Composers\FooterComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        EventRegistration::observe(EventRegistrationObserver::class);
        
        // Register view composers
        View::composer('layouts.footer', FooterComposer::class);
        
        // Register email event listeners
        Event::listen(MessageSending::class, \App\Listeners\LogSendingEmail::class);
        Event::listen(MessageSent::class, \App\Listeners\LogSentEmail::class);
    }
}
