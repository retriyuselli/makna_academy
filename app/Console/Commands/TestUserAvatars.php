<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestUserAvatars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:avatars {--user= : Specific user email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user avatar URLs and helper functions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🖼️  Testing User Avatars');
        $this->newLine();

        if ($userEmail = $this->option('user')) {
            $user = User::where('email', $userEmail)->first();
            if (!$user) {
                $this->error("User with email '{$userEmail}' not found.");
                return 1;
            }
            $this->testSingleUser($user);
        } else {
            $this->testMultipleUsers();
        }

        return 0;
    }

    private function testSingleUser(User $user)
    {
        $this->info("Testing user: {$user->name} ({$user->email})");
        $this->newLine();

        $this->comment('Database Value:');
        $this->line('Raw avatar: ' . ($user->avatar ?? 'NULL'));
        $this->line('Google ID: ' . ($user->google_id ?? 'NULL'));
        $this->newLine();

        $this->comment('Helper Function Results:');
        $this->line('user_avatar(): ' . user_avatar($user));
        $this->line('user_avatar(size=50): ' . user_avatar($user, 50));
        $this->line('default_avatar(): ' . default_avatar(150, $user->name));
        $this->newLine();

        $this->comment('Validation:');
        $isUrl = filter_var($user->avatar, FILTER_VALIDATE_URL);
        $this->line('Is URL: ' . ($isUrl ? 'Yes ✅' : 'No ❌'));
        
        if ($user->avatar && !$isUrl) {
            $hasStorage = str_starts_with($user->avatar, 'avatars/');
            $this->line('Storage path: ' . ($hasStorage ? 'Yes ✅' : 'No ❌'));
        }

        // Test avatar accessibility
        $avatarUrl = user_avatar($user);
        $this->newLine();
        $this->comment('Avatar URL Test:');
        $this->line($avatarUrl);
    }

    private function testMultipleUsers()
    {
        // Test Google users
        $googleUsers = User::whereNotNull('google_id')->limit(3)->get();
        $regularUsers = User::whereNull('google_id')->limit(3)->get();

        $this->info('Google Users:');
        foreach ($googleUsers as $user) {
            $this->line("• {$user->name}: " . user_avatar($user));
        }
        $this->newLine();

        $this->info('Regular Users (with default avatars):');
        foreach ($regularUsers as $user) {
            $this->line("• {$user->name}: " . user_avatar($user));
        }
        $this->newLine();

        $this->comment('Component test URLs:');
        $this->line('You can test these in a Blade view with:');
        $this->line('<x-user-avatar :user="$user" :size="100" :show-name="true" />');
    }
}
