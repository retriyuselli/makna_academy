<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailSending extends Command
{
    protected $signature = 'email:test {email : Email address to test} {--type=simple : Type of test (simple, verification)}';
    protected $description = 'Test email sending functionality';

    public function handle()
    {
        $email = $this->argument('email');
        $type = $this->option('type');
        
        $this->info("🧪 Testing email sending to: {$email}");
        $this->info("📧 Email provider: " . config('mail.default'));
        $this->line('');
        
        try {
            if ($type === 'verification') {
                $this->testVerificationEmail($email);
            } else {
                $this->testSimpleEmail($email);
            }
            
            $this->info('✅ Email sent successfully!');
            $this->info('📬 Please check the inbox (and spam folder) of: ' . $email);
            
            // Wait a moment for listeners to process
            sleep(2);
            
            // Check email logs
            $this->line('');
            $this->info('📊 Email Activity Log:');
            $this->call('email:check-user', ['email' => $email]);
            
        } catch (\Exception $e) {
            $this->error('❌ Error sending email: ' . $e->getMessage());
            
            $this->line('');
            $this->warn('🔧 Troubleshooting:');
            $this->line('• Check your internet connection');
            $this->line('• Verify SMTP credentials in .env file');
            $this->line('• Make sure Gmail App Password is correct');
            $this->line('• Check if 2-Step Verification is enabled on Gmail');
            
            return 1;
        }
        
        return 0;
    }
    
    private function testSimpleEmail($email)
    {
        Mail::raw('🎉 This is a test email from Makna Academy! If you received this, email configuration is working correctly.', function($message) use ($email) {
            $message->to($email)
                    ->subject('✅ Test Email - Makna Academy Email System');
        });
    }
    
    private function testVerificationEmail($email)
    {
        // Create or find test user
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]
        );
        
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->info('📧 Verification email sent!');
        } else {
            $this->warn('⚠️  Email already verified. Sending anyway...');
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        }
    }
}
