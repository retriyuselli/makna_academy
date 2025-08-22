<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestSmartVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smart-verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test smart verification middleware logic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Smart Email Verification Logic...');
        
        // Get Google users
        $googleUsers = User::whereNotNull('google_id')->get();
        $this->info("Found {$googleUsers->count()} Google users:");
        
        foreach ($googleUsers as $user) {
            $status = $user->email_verified_at ? 'VERIFIED' : 'NOT VERIFIED';
            $this->line("- {$user->name} ({$user->email}) - Google ID: {$user->google_id} - Status: {$status}");
        }
        
        // Test the logic
        $this->info("\nTesting middleware logic:");
        foreach ($googleUsers as $user) {
            if ($user->google_id && is_null($user->email_verified_at)) {
                $this->warn("User {$user->name} would be auto-verified");
            } else {
                $this->info("User {$user->name} already verified or would pass through");
            }
        }
        
        // Show regular users
        $regularUsers = User::whereNull('google_id')->get();
        $this->info("\nRegular users (non-Google):");
        foreach ($regularUsers as $user) {
            $status = $user->email_verified_at ? 'VERIFIED' : 'NOT VERIFIED';
            $action = $user->email_verified_at ? 'would pass' : 'would be redirected to verification';
            $this->line("- {$user->name} ({$user->email}) - Status: {$status} - Action: {$action}");
        }
        
        return 0;
    }
}
