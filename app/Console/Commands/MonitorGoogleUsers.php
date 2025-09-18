<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MonitorGoogleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:monitor-users {--stats : Show statistics only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor users who registered/logged in via Google OAuth';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👥 Google OAuth Users Monitor');
        $this->newLine();

        // Get statistics
        $totalUsers = User::count();
        $googleUsers = User::whereNotNull('google_id')->count();
        $regularUsers = User::whereNull('google_id')->count();
        $recentGoogleUsers = User::whereNotNull('google_id')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Show statistics
        $this->info('📊 Statistics:');
        $this->comment("Total Users: {$totalUsers}");
        $this->comment("Google Users: {$googleUsers} (" . round(($googleUsers / max($totalUsers, 1)) * 100, 1) . "%)");
        $this->comment("Regular Users: {$regularUsers} (" . round(($regularUsers / max($totalUsers, 1)) * 100, 1) . "%)");
        $this->comment("New Google Users (Last 7 days): {$recentGoogleUsers}");
        $this->newLine();

        if ($this->option('stats')) {
            return 0;
        }

        // Show recent Google users
        $recentUsers = User::whereNotNull('google_id')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if ($recentUsers->count() > 0) {
            $this->info('🔥 Recent Google Users:');
            $headers = ['Name', 'Email', 'Registered', 'Role', 'Verified', 'Avatar'];
            $rows = [];
            
            foreach ($recentUsers as $user) {
                $avatarStatus = $user->avatar_url ? '✅ Yes' : '❌ No';
                
                $rows[] = [
                    $user->name,
                    $user->email,
                    $user->created_at->format('d/m/Y'),
                    $user->role,
                    $user->email_verified_at ? '✅ Yes' : '❌ No',
                    $avatarStatus
                ];
            }
            
            $this->table($headers, $rows);
        } else {
            $this->comment('No Google users found yet.');
            $this->newLine();
            $this->comment('🚀 Test Google login:');
            $this->comment(url('auth/google/redirect'));
        }

        $this->newLine();

        // Show users without Google ID but could be linked
        $usersWithoutGoogle = User::whereNull('google_id')
            ->whereNotNull('email_verified_at')
            ->limit(5)
            ->get();

        if ($usersWithoutGoogle->count() > 0) {
            $this->info('🔗 Users that could link with Google:');
            foreach ($usersWithoutGoogle as $user) {
                $this->comment("- {$user->name} ({$user->email})");
            }
            $this->newLine();
            $this->comment('These users can use "Login with Google" to link their accounts.');
        }

        return 0;
    }
}
