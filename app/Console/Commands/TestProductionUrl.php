<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestProductionUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:production-url {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test production URL accessibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = $this->argument('url') ?: 'https://maknaacademy.com';
        
        $this->info("🌐 Testing Production URLs");
        $this->line("Base URL: {$baseUrl}");
        $this->line("========================");
        
        $urls = [
            '/' => 'Homepage',
            '/admin' => 'Filament Admin Panel',
            '/events' => 'Events List',
            '/events/list' => 'Events List (Alias)',
            '/dashboard' => 'User Dashboard',
            '/auth/google/redirect' => 'Google OAuth',
        ];
        
        foreach ($urls as $path => $description) {
            $fullUrl = $baseUrl . $path;
            $this->line("Testing: {$description}");
            $this->line("URL: {$fullUrl}");
            
            try {
                $response = Http::timeout(10)->get($fullUrl);
                
                if ($response->successful()) {
                    $this->info("✅ SUCCESS - Status: {$response->status()}");
                } elseif ($response->status() === 404) {
                    $this->error("❌ 404 NOT FOUND");
                } elseif ($response->status() === 403) {
                    $this->warn("⚠️  403 FORBIDDEN - Check permissions/routing");
                } elseif ($response->status() === 500) {
                    $this->error("💥 500 SERVER ERROR - Check logs");
                } else {
                    $this->warn("⚠️  Status: {$response->status()}");
                }
            } catch (\Exception $e) {
                $this->error("❌ ERROR: " . $e->getMessage());
            }
            
            $this->line("");
        }
        
        // SSH-specific checks
        $this->info("� SSH Environment Checks:");
        $this->line("Current directory: " . getcwd());
        $this->line("PHP version: " . PHP_VERSION);
        $this->line("Laravel version: " . app()->version());
        
        // Check critical files
        $this->info("\n📁 Critical Files Check:");
        $files = [
            '.env' => 'Environment config',
            'public/.htaccess' => 'URL rewriting',
            'storage' => 'Storage directory',
            'bootstrap/cache' => 'Bootstrap cache',
        ];
        
        foreach ($files as $file => $desc) {
            if (file_exists($file)) {
                $perms = substr(sprintf('%o', fileperms($file)), -4);
                $this->line("✅ {$desc}: {$file} (permissions: {$perms})");
            } else {
                $this->error("❌ {$desc}: {$file} NOT FOUND");
            }
        }
        
        $this->info("\n💡 SSH Troubleshooting Commands:");
        $this->line("1. Check current directory: pwd");
        $this->line("2. List files: ls -la");
        $this->line("3. Check PHP version: php -v");
        $this->line("4. Run deployment: bash production-deploy.sh");
        $this->line("5. Check error logs: tail -f storage/logs/laravel.log");
        
        return 0;
    }
}
