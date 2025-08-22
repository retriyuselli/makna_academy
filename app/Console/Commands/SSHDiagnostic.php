<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SSHDiagnostic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssh:diagnostic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete SSH server diagnostic for Niagahoster deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 SSH Server Diagnostic for Niagahoster');
        $this->line('==========================================');
        
        // 1. Environment Check
        $this->info('🌍 Environment Information:');
        $this->line('Current Directory: ' . getcwd());
        $this->line('PHP Version: ' . PHP_VERSION);
        $this->line('Laravel Version: ' . app()->version());
        $this->line('App Environment: ' . config('app.env'));
        $this->line('App Debug: ' . (config('app.debug') ? 'ON' : 'OFF'));
        $this->line('App URL: ' . config('app.url'));
        $this->line('');
        
        // 2. File System Check
        $this->info('📁 Critical Files & Directories:');
        $checks = [
            '.env' => 'Environment configuration',
            'public/.htaccess' => 'URL rewriting rules',
            'storage' => 'Storage directory',
            'bootstrap/cache' => 'Bootstrap cache',
            'vendor' => 'Composer dependencies',
            'app/Http/Controllers/Event/EventController.php' => 'Event controller',
        ];
        
        foreach ($checks as $path => $description) {
            if (file_exists($path)) {
                $perms = is_dir($path) ? 'DIR' : substr(sprintf('%o', fileperms($path)), -4);
                $this->line("✅ {$description}: {$path} ({$perms})");
            } else {
                $this->error("❌ {$description}: {$path} MISSING");
            }
        }
        $this->line('');
        
        // 3. Database Check
        $this->info('🗄️  Database Connection:');
        try {
            DB::connection()->getPdo();
            $this->line('✅ Database connection successful');
            
            $tables = ['users', 'events', 'event_categories'];
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $this->line("✅ Table '{$table}': {$count} records");
                } catch (\Exception $e) {
                    $this->error("❌ Table '{$table}': " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
        }
        $this->line('');
        
        // 4. Routes Check
        $this->info('🛣️  Routes Check:');
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return str_contains($route->uri(), 'events') || $route->uri() === 'admin' || $route->uri() === '/';
        });
        
        foreach ($routes as $route) {
            $methods = implode('|', $route->methods());
            $this->line("✅ {$methods} /{$route->uri()} → {$route->getActionName()}");
        }
        $this->line('');
        
        // 5. Permissions Check
        $this->info('🔐 Permissions Check:');
        $paths = ['storage', 'bootstrap/cache', 'public'];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path) ? 'WRITABLE' : 'NOT WRITABLE';
                $this->line("✅ {$path}: {$perms} ({$writable})");
            }
        }
        $this->line('');
        
        // 6. Admin Users Check
        $this->info('👤 Admin Users Status:');
        try {
            $admins = DB::table('users')->whereIn('role', ['super_admin', 'admin'])->get();
            foreach ($admins as $admin) {
                $verified = $admin->email_verified_at ? 'VERIFIED' : 'NOT VERIFIED';
                $this->line("✅ {$admin->name} ({$admin->email}) - {$admin->role} - {$verified}");
            }
        } catch (\Exception $e) {
            $this->error('❌ Cannot check admin users: ' . $e->getMessage());
        }
        $this->line('');
        
        // 7. Google OAuth Check
        $this->info('🔑 Google OAuth Configuration:');
        $googleConfig = [
            'GOOGLE_CLIENT_ID' => config('services.google.client_id'),
            'GOOGLE_CLIENT_SECRET' => config('services.google.client_secret'),
            'GOOGLE_REDIRECT_URI' => config('services.google.redirect'),
        ];
        
        foreach ($googleConfig as $key => $value) {
            if ($value) {
                $display = $key === 'GOOGLE_CLIENT_SECRET' ? substr($value, 0, 10) . '...' : $value;
                $this->line("✅ {$key}: {$display}");
            } else {
                $this->error("❌ {$key}: NOT SET");
            }
        }
        $this->line('');
        
        // 8. Quick Fix Commands
        $this->info('⚡ Quick Fix Commands for SSH:');
        $this->warn('Run these if you have issues:');
        $this->line('php artisan admin:verify-all');
        $this->line('php artisan config:clear && php artisan config:cache');
        $this->line('php artisan route:clear && php artisan route:cache');
        $this->line('chmod -R 755 storage bootstrap/cache');
        $this->line('bash production-deploy.sh');
        
        return 0;
    }
}
