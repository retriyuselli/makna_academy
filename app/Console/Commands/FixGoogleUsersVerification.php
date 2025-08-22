<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixGoogleUsersVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:fix-verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-verify email for Google OAuth users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Fixing Google Users Email Verification...');
        $this->newLine();

        // Find Google users that are not verified
        $unverifiedGoogleUsers = User::whereNotNull('google_id')
            ->whereNull('email_verified_at')
            ->get();

        if ($unverifiedGoogleUsers->count() === 0) {
            $this->info('✅ All Google users are already verified!');
            return 0;
        }

        $this->info("Found {$unverifiedGoogleUsers->count()} Google users that need verification:");
        $this->newLine();

        foreach ($unverifiedGoogleUsers as $user) {
            $this->comment("- {$user->name} ({$user->email})");
        }

        $this->newLine();

        if (!$this->confirm('Auto-verify these users?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $updated = 0;
        foreach ($unverifiedGoogleUsers as $user) {
            $user->update([
                'email_verified_at' => now()
            ]);
            $updated++;
            
            $this->info("✅ Verified: {$user->name}");
        }

        $this->newLine();
        $this->info("🎉 Successfully verified {$updated} Google users!");
        
        // Show summary
        $this->call('oauth:monitor-users', ['--stats' => true]);

        return 0;
    }
}
