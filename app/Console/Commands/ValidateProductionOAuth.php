<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ValidateProductionOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:validate-production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate Google OAuth setup for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Production Google OAuth Validation');
        $this->line('=====================================');
        
        // Production credentials from user
        $prodClientId = '1073847707519-lgdnl2f23rddc1fal2hja9r5bro8jdvp.apps.googleusercontent.com';
        $prodSecret = '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';
        $prodRedirect = 'https://maknaacademy.com/auth/google/callback';
        
        $this->info('📋 Production Configuration Analysis:');
        $this->line("Client ID: {$prodClientId}");
        $this->line("Secret: " . substr($prodSecret, 0, 10) . "...");
        $this->line("Redirect URI: {$prodRedirect}");
        
        // Validate format
        $this->info("\n✅ Format Validation:");
        
        if (str_starts_with($prodClientId, '1073847707519-') && str_ends_with($prodClientId, '.apps.googleusercontent.com')) {
            $this->line('✅ Client ID format valid');
        } else {
            $this->error('❌ Client ID format invalid');
        }
        
        if (str_starts_with($prodSecret, 'GOCSPX-') && strlen($prodSecret) > 20) {
            $this->line('✅ Client Secret format valid');
        } else {
            $this->error('❌ Client Secret format invalid');
        }
        
        if (str_starts_with($prodRedirect, 'https://maknaacademy.com/') && str_contains($prodRedirect, 'callback')) {
            $this->line('✅ Redirect URI format valid');
        } else {
            $this->error('❌ Redirect URI format invalid');
        }
        
        // Google Cloud Console requirements
        $this->info("\n🔧 Google Cloud Console Checklist:");
        $this->line("1. ✅ Authorized JavaScript origins:");
        $this->line("   - https://maknaacademy.com");
        $this->line("2. ✅ Authorized redirect URIs:");
        $this->line("   - https://maknaacademy.com/auth/google/callback");
        
        // Production .env template
        $this->info("\n📄 Production .env Configuration:");
        $this->warn("Copy this to your production .env file:");
        $this->line("GOOGLE_CLIENT_ID={$prodClientId}");
        $this->line("GOOGLE_CLIENT_SECRET={$prodSecret}");
        $this->line("GOOGLE_REDIRECT_URI={$prodRedirect}");
        
        // Test URLs
        $this->info("\n🔗 Test URLs for Production:");
        $this->line("Google Login: https://maknaacademy.com/auth/google/redirect");
        $this->line("Callback URL: https://maknaacademy.com/auth/google/callback");
        
        // Troubleshooting
        $this->info("\n🚨 Common Issues & Solutions:");
        $this->line("• 404 on /events → Run deployment commands");
        $this->line("• OAuth errors → Check Google Cloud Console settings");
        $this->line("• 403 errors → Check domain document root points to /public");
        
        return 0;
    }
}
