<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DebugEmailConfig extends Command
{
    protected $signature = 'email:debug';
    protected $description = 'Debug email configuration';

    public function handle()
    {
        $this->info('🔍 Email Configuration Debug');
        $this->line('');
        
        // Show current config
        $config = [
            'MAIL_MAILER' => config('mail.default'),
            'MAIL_HOST' => config('mail.mailers.smtp.host'),
            'MAIL_PORT' => config('mail.mailers.smtp.port'),
            'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
            'MAIL_PASSWORD' => strlen(config('mail.mailers.smtp.password')) . ' characters',
            'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
            'MAIL_FROM_ADDRESS' => config('mail.from.address'),
            'MAIL_FROM_NAME' => config('mail.from.name'),
        ];
        
        $this->table(['Setting', 'Value'], collect($config)->map(function($value, $key) {
            return [$key, $value];
        })->toArray());
        
        $this->line('');
        $this->info('🧪 Testing SMTP Connection...');
        
        try {
            // Simple connection test
            $transport = Mail::getSwiftMailer()->getTransport();
            if (method_exists($transport, 'start')) {
                $transport->start();
                $this->info('✅ SMTP connection successful!');
                
                if (method_exists($transport, 'stop')) {
                    $transport->stop();
                }
            } else {
                $this->warn('⚠️  Cannot test connection with current mail driver');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ SMTP connection failed: ' . $e->getMessage());
            
            $this->line('');
            $this->warn('🔧 Common Solutions:');
            $this->line('• Generate new App Password from Google Account');
            $this->line('• Make sure 2-Step Verification is enabled');
            $this->line('• Check if App Password has spaces (remove them)');
            $this->line('• Try using port 465 with SSL encryption');
        }
        
        return 0;
    }
}
