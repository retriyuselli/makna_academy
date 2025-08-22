<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class TestGoogleOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:test-google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google OAuth configuration and setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Google OAuth Configuration...');
        $this->newLine();

        // Check environment variables
        $this->info('📋 Environment Variables:');
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        if (empty($clientId)) {
            $this->error('❌ GOOGLE_CLIENT_ID not set in .env');
        } else {
            $this->info('✅ GOOGLE_CLIENT_ID: ' . substr($clientId, 0, 20) . '...');
        }

        if (empty($clientSecret)) {
            $this->error('❌ GOOGLE_CLIENT_SECRET not set in .env');
        } else {
            $this->info('✅ GOOGLE_CLIENT_SECRET: ' . substr($clientSecret, 0, 10) . '...');
        }

        if (empty($redirectUri)) {
            $this->error('❌ GOOGLE_REDIRECT_URI not set in .env');
        } else {
            $this->info('✅ GOOGLE_REDIRECT_URI: ' . $redirectUri);
        }

        $this->newLine();

        // Check routes
        $this->info('🛣️  Routes:');
        $routes = Route::getRoutes();
        $googleRoutes = [];
        
        foreach ($routes as $route) {
            if (str_contains($route->uri(), 'auth/google')) {
                $googleRoutes[] = $route;
            }
        }

        if (count($googleRoutes) >= 2) {
            $this->info('✅ Google OAuth routes found:');
            foreach ($googleRoutes as $route) {
                $this->info('   - ' . $route->methods()[0] . ' ' . url($route->uri()));
            }
        } else {
            $this->error('❌ Google OAuth routes not found');
        }

        $this->newLine();

        // Check database
        $this->info('🗃️  Database:');
        try {
            $hasGoogleId = Schema::hasColumn('users', 'google_id');
            if ($hasGoogleId) {
                $this->info('✅ google_id column exists in users table');
            } else {
                $this->error('❌ google_id column not found in users table');
            }
        } catch (\Exception $e) {
            $this->error('❌ Database connection error: ' . $e->getMessage());
        }

        $this->newLine();

        // Check Laravel Socialite
        $this->info('📦 Laravel Socialite:');
        if (class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            $this->info('✅ Laravel Socialite installed');
        } else {
            $this->error('❌ Laravel Socialite not installed');
        }

        $this->newLine();

        // Summary
        $allGood = !empty($clientId) && !empty($clientSecret) && !empty($redirectUri) && 
                  count($googleRoutes) >= 2 && class_exists(\Laravel\Socialite\Facades\Socialite::class);

        if ($allGood) {
            $this->info('🎉 Google OAuth setup looks good!');
            $this->newLine();
            $this->comment('Next steps:');
            $this->comment('1. Setup Google Cloud Console credentials');
            $this->comment('2. Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET to .env');
            $this->comment('3. Test login at: ' . url('auth/google/redirect'));
        } else {
            $this->error('⚠️  Some issues found. Please check the configuration above.');
        }

        return 0;
    }
}
