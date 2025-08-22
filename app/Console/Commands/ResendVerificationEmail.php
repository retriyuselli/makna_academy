<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResendVerificationEmail extends Command
{
    protected $signature = 'email:resend-verification {email : User email address}';
    protected $description = 'Resend verification email to a specific user';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User with email '{$email}' not found.");
            return 1;
        }
        
        $this->info("👤 User found: {$user->name}");
        
        if ($user->hasVerifiedEmail()) {
            $this->warn("⚠️  Email already verified at: {$user->email_verified_at}");
            
            if (!$this->confirm('Do you want to reset verification and send email anyway?')) {
                return 0;
            }
            
            // Reset verification
            $user->email_verified_at = null;
            $user->save();
            $this->info("🔄 Email verification reset.");
        }
        
        try {
            $this->info("📧 Sending verification email to: {$email}");
            $user->sendEmailVerificationNotification();
            $this->info("✅ Verification email sent successfully!");
            
            $this->line('');
            $this->info("📬 Please check the inbox (and spam folder) of: {$email}");
            $this->info("🔗 The email contains a verification link that expires in 60 minutes.");
            
            // Wait and check logs
            sleep(2);
            $this->line('');
            $this->call('email:check-user', ['email' => $email]);
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send verification email: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
