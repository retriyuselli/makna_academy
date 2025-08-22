<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupEmailConfig extends Command
{
    protected $signature = 'email:setup {--provider=gmail : Email provider (gmail, outlook, etc)}';
    protected $description = 'Setup email configuration for sending real emails';

    public function handle()
    {
        $provider = $this->option('provider');
        
        $this->info("🚀 Setting up email configuration for {$provider}...");
        $this->line('');
        
        if ($provider === 'gmail') {
            $this->setupGmail();
        } else {
            $this->error("Provider '{$provider}' not supported yet.");
            return 1;
        }
        
        return 0;
    }
    
    private function setupGmail()
    {
        $this->warn('📧 Gmail SMTP Setup Instructions:');
        $this->line('');
        
        $steps = [
            '1. Go to Google Account settings: https://myaccount.google.com/',
            '2. Navigate to Security > 2-Step Verification (must be enabled)',
            '3. Go to Security > App passwords',
            '4. Generate an App Password for "Mail"',
            '5. Copy the 16-character app password',
            '6. Update the MAIL_PASSWORD in your .env file',
        ];
        
        foreach ($steps as $step) {
            $this->line("   {$step}");
        }
        
        $this->line('');
        $this->info('📝 Current Email Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['MAIL_MAILER', config('mail.default')],
                ['MAIL_HOST', config('mail.mailers.smtp.host')],
                ['MAIL_PORT', config('mail.mailers.smtp.port')],
                ['MAIL_USERNAME', config('mail.mailers.smtp.username')],
                ['MAIL_FROM_ADDRESS', config('mail.from.address')],
                ['MAIL_FROM_NAME', config('mail.from.name')],
            ]
        );
        
        $this->line('');
        $this->warn('⚠️  Remember to:');
        $this->line('   • Replace "your_app_password_here" with your actual Gmail App Password');
        $this->line('   • Run: php artisan config:clear after updating .env');
        $this->line('   • Test with: php artisan email:test your-email@domain.com');
        
        if ($this->confirm('Do you want to test email sending now?')) {
            $email = $this->ask('Enter test email address');
            if ($email) {
                $this->call('email:test', ['email' => $email]);
            }
        }
    }
}
