<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class VerifyAdminUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-verify all admin users for production deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Auto-Verifying Admin Users for Production');
        $this->line('===========================================');
        
        // Get all admin users (super_admin and admin roles)
        $adminUsers = User::whereIn('role', ['super_admin', 'admin'])
            ->whereNull('email_verified_at')
            ->get();
        
        if ($adminUsers->isEmpty()) {
            $this->info('✅ All admin users are already verified!');
            
            // Show verified admin users
            $verifiedAdmins = User::whereIn('role', ['super_admin', 'admin'])
                ->whereNotNull('email_verified_at')
                ->get();
                
            foreach ($verifiedAdmins as $admin) {
                $this->line("✅ {$admin->name} ({$admin->email}) - {$admin->role} - Verified");
            }
            
            return 0;
        }
        
        $this->info("Found {$adminUsers->count()} unverified admin users:");
        
        foreach ($adminUsers as $user) {
            $this->line("🔍 {$user->name} ({$user->email}) - {$user->role}");
        }
        
        $this->line('');
        $confirm = $this->confirm('Auto-verify these admin users?', true);
        
        if (!$confirm) {
            $this->warn('Operation cancelled.');
            return 1;
        }
        
        $this->info('⚡ Verifying admin users...');
        
        foreach ($adminUsers as $user) {
            $user->email_verified_at = now();
            $user->save();
            
            $this->line("✅ Verified: {$user->name} ({$user->email})");
        }
        
        $this->info("\n🎉 All admin users verified successfully!");
        $this->warn("📝 Admin users can now:");
        $this->line("• Login to admin@maknaacademy.com");
        $this->line("• Access Filament admin panel at /admin");
        $this->line("• No email verification required");
        
        return 0;
    }
}
