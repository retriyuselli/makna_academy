<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DebugAvatarDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:avatar {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug avatar display for specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            $email = $this->ask('Enter user email to debug');
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('User not found!');
            return 1;
        }
        
        $this->info("🔍 Debugging Avatar for: {$user->name}");
        $this->newLine();
        
        $this->info('📊 User Details:');
        $this->comment("Name: {$user->name}");
        $this->comment("Email: {$user->email}"); 
        $this->comment("Role: {$user->role}");
        $this->comment("Google ID: " . ($user->google_id ?? 'NULL'));
        $this->comment("Email Verified: " . ($user->email_verified_at ? '✅ Yes' : '❌ No'));
        $this->newLine();
        
        $this->info('🖼️ Avatar Details:');
        $this->comment("Raw Avatar Value: " . ($user->avatar_url ?? 'NULL'));
        $this->comment("Is URL: " . (filter_var($user->avatar_url, FILTER_VALIDATE_URL) ? '✅ Yes' : '❌ No'));
        $this->newLine();
        
        $this->info('🎯 Helper Function Results:');
        $avatarUrl = user_avatar($user);
        $this->comment("user_avatar(): {$avatarUrl}");
        
        $defaultUrl = default_avatar(150, $user->name);
        $this->comment("default_avatar(): {$defaultUrl}");
        $this->newLine();
        
        $this->info('🧪 Test URLs:');
        $sizes = [32, 50, 80, 100];
        foreach ($sizes as $size) {
            $url = user_avatar($user, $size);
            $this->comment("{$size}px: {$url}");
        }
        $this->newLine();
        
        // Test if URL is accessible
        $this->info('🌐 URL Accessibility Test:');
        try {
            $headers = get_headers($avatarUrl, 1);
            $status = $headers[0];
            $this->comment("Status: {$status}");
            
            if (strpos($status, '200') !== false) {
                $this->info('✅ Avatar URL is accessible!');
            } else {
                $this->error('❌ Avatar URL not accessible!');
            }
        } catch (\Exception $e) {
            $this->error('❌ Failed to test URL: ' . $e->getMessage());
        }
        
        return 0;
    }
}
