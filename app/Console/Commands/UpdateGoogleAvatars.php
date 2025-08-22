<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateGoogleAvatars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:update-avatars {--force : Force re-download all avatars}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and store Google user avatars locally';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🖼️  Updating Google User Avatars...');
        $this->newLine();

        // Find Google users
        $googleUsers = User::whereNotNull('google_id');
        
        if ($this->option('force')) {
            $googleUsers = $googleUsers->get();
            $this->comment('Force mode: Processing all Google users');
        } else {
            // Only users without proper avatar URLs
            $googleUsers = $googleUsers->where(function($query) {
                $query->whereNull('avatar')
                      ->orWhere('avatar', 'not like', 'http%');
            })->get();
        }

        if ($googleUsers->count() === 0) {
            $this->info('✅ No users need avatar updates.');
            return 0;
        }

        $this->info("Found {$googleUsers->count()} users to process:");
        $this->newLine();

        $bar = $this->output->createProgressBar($googleUsers->count());
        $bar->start();

        $updated = 0;
        foreach ($googleUsers as $user) {
            if ($this->processUserAvatar($user)) {
                $updated++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("🎉 Successfully updated {$updated} user avatars!");
        
        return 0;
    }

    private function processUserAvatar(User $user): bool
    {
        try {
            // Generate a better default avatar URL or try to fetch original Google avatar
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                // If we have a local path, create a proper URL or generate default
                $avatarUrl = default_avatar(200, $user->name);
                
                $user->update(['avatar' => $avatarUrl]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            $this->error("Failed to process {$user->name}: " . $e->getMessage());
            return false;
        }
    }
}
