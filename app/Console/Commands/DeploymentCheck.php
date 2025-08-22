<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Event;

class DeploymentCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check deployment status and common issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Deployment Check for Production');
        $this->line('=====================================');
        
        // Check Environment
        $this->info('📊 Environment Info:');
        $this->line('APP_ENV: ' . config('app.env'));
        $this->line('APP_URL: ' . config('app.url'));
        $this->line('APP_DEBUG: ' . (config('app.debug') ? 'true' : 'false'));
        
        // Check Routes
        $this->info("\n🛣️  Event Routes Check:");
        $eventRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return str_starts_with($route->uri(), 'events');
        });
        
        foreach ($eventRoutes as $route) {
            $this->line("✅ {$route->methods()[0]} /{$route->uri()} → {$route->getActionName()}");
        }
        
        // Check Controllers
        $this->info("\n📦 Controller Check:");
        if (class_exists('App\Http\Controllers\Event\EventController')) {
            $this->line('✅ EventController class exists');
            
            $controller = new \App\Http\Controllers\Event\EventController();
            if (method_exists($controller, 'index')) {
                $this->line('✅ index() method exists');
            } else {
                $this->error('❌ index() method missing');
            }
        } else {
            $this->error('❌ EventController class not found');
        }
        
        // Check Database
        $this->info("\n🗄️  Database Check:");
        try {
            $eventCount = Event::count();
            $this->line("✅ Events table accessible: {$eventCount} events found");
        } catch (\Exception $e) {
            $this->error("❌ Database error: " . $e->getMessage());
        }
        
        // Check View
        $this->info("\n👁️  View Check:");
        $viewPath = resource_path('views/event/index.blade.php');
        if (file_exists($viewPath)) {
            $this->line('✅ events index view exists');
        } else {
            $this->error('❌ events index view missing');
        }
        
        // Production Commands
        $this->info("\n🔧 Production Deployment Commands:");
        $this->warn("Run these commands on production server:");
        $this->line("php artisan config:cache");
        $this->line("php artisan route:cache");
        $this->line("php artisan view:cache");
        $this->line("composer dump-autoload --optimize");
        
        // .htaccess check
        $this->info("\n⚙️  .htaccess Check:");
        $htaccessPath = public_path('.htaccess');
        if (file_exists($htaccessPath)) {
            $this->line('✅ .htaccess file exists');
        } else {
            $this->error('❌ .htaccess file missing - may cause 404 issues');
        }
        
        return 0;
    }
}
