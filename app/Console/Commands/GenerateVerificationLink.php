<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class GenerateVerificationLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:generate-link {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fresh email verification link for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        if ($user->email_verified_at) {
            $this->info("User {$user->name} ({$email}) is already verified!");
            return 0;
        }
        
        // Generate verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );
        
        $this->info("🔗 Fresh verification link generated:");
        $this->line($verificationUrl);
        $this->info("\n📝 Instructions:");
        $this->line("1. Copy the link above");
        $this->line("2. Open in browser while server is running");
        $this->line("3. Link expires in 60 minutes");
        $this->warn("\n⚠️  Make sure server is running: php artisan serve");
        
        return 0;
    }
}
