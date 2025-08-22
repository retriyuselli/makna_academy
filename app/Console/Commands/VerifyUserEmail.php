<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUserEmail extends Command
{
    protected $signature = 'user:verify-email {email : User email to verify}';
    protected $description = 'Manually verify user email (for development/testing)';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User with email '{$email}' not found.");
            return 1;
        }
        
        if ($user->hasVerifiedEmail()) {
            $this->info("✅ Email already verified for: {$user->name} ({$email})");
            $this->info("🕐 Verified at: {$user->email_verified_at}");
            return 0;
        }
        
        // Verify the email
        $user->markEmailAsVerified();
        
        $this->info("🎉 Email verified successfully!");
        $this->info("👤 User: {$user->name}");
        $this->info("📧 Email: {$email}");
        $this->info("🕐 Verified at: {$user->email_verified_at}");
        
        $this->line('');
        $this->info("🚀 User can now:");
        $this->line("   • Login to their account");
        $this->line("   • Register for events");
        $this->line("   • Access all verified features");
        
        return 0;
    }
}
