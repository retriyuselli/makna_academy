<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupGoogleCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:setup-google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive setup for Google OAuth credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Google OAuth Credentials Setup');
        $this->newLine();

        // Check current configuration
        $currentClientId = config('services.google.client_id');
        $currentClientSecret = config('services.google.client_secret');
        $currentRedirectUri = config('services.google.redirect');

        if (!empty($currentClientId) && !empty($currentClientSecret)) {
            $this->info('✅ Current Google OAuth credentials found:');
            $this->comment('Client ID: ' . substr($currentClientId, 0, 20) . '...');
            $this->comment('Redirect URI: ' . $currentRedirectUri);
            $this->newLine();

            if (!$this->confirm('Do you want to update the credentials?')) {
                $this->info('Setup cancelled.');
                return 0;
            }
        }

        $this->newLine();
        $this->comment('📚 Instructions:');
        $this->comment('1. Go to https://console.cloud.google.com/');
        $this->comment('2. Create/select project');
        $this->comment('3. Enable Google+ API or People API');
        $this->comment('4. Create OAuth 2.0 Client ID');
        $this->comment('5. Add authorized redirect URI: ' . url('auth/google/callback'));
        $this->newLine();

        // Get Client ID
        $clientId = $this->ask('Enter your Google Client ID');
        if (empty($clientId)) {
            $this->error('Client ID is required!');
            return 1;
        }

        // Get Client Secret
        $clientSecret = $this->secret('Enter your Google Client Secret');
        if (empty($clientSecret)) {
            $this->error('Client Secret is required!');
            return 1;
        }

        // Confirm redirect URI
        $defaultRedirectUri = url('auth/google/callback');
        $redirectUri = $this->ask('Redirect URI', $defaultRedirectUri);

        $this->newLine();
        $this->info('📝 Configuration to be added:');
        $this->comment('GOOGLE_CLIENT_ID=' . substr($clientId, 0, 20) . '...');
        $this->comment('GOOGLE_CLIENT_SECRET=' . substr($clientSecret, 0, 10) . '...');
        $this->comment('GOOGLE_REDIRECT_URI=' . $redirectUri);
        $this->newLine();

        if (!$this->confirm('Save these credentials to .env file?')) {
            $this->info('Setup cancelled.');
            return 0;
        }

        // Update .env file
        $this->updateEnvFile([
            'GOOGLE_CLIENT_ID' => $clientId,
            'GOOGLE_CLIENT_SECRET' => $clientSecret,
            'GOOGLE_REDIRECT_URI' => $redirectUri,
        ]);

        $this->newLine();
        $this->info('✅ Google OAuth credentials saved successfully!');
        $this->newLine();

        $this->comment('🧪 Test your setup:');
        $this->comment('php artisan oauth:test-google');
        $this->newLine();

        $this->comment('🚀 Try login:');
        $this->comment(url('auth/google/redirect'));

        return 0;
    }

    /**
     * Update environment file with new values
     */
    private function updateEnvFile(array $values)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                // Update existing key
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                // Add new key
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envPath, $envContent);

        // Clear config cache
        $this->call('config:clear');
    }
}
