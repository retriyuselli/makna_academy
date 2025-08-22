<?php

namespace App\Console\Commands;

use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Console\Command;

class CheckUserEmailStatus extends Command
{
    protected $signature = 'email:check-user {email : User email to check}';
    protected $description = 'Check email status for a specific user';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Check if user exists
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $this->info("Email Status for: {$email}");
        $this->info("User: {$user->name}");
        $this->info("Email Verified: " . ($user->email_verified_at ? 'Yes (' . $user->email_verified_at . ')' : 'No'));
        $this->line('');

        // Get email logs
        $emailLogs = EmailLog::forEmail($email)->latest()->get();

        if ($emailLogs->isEmpty()) {
            $this->warn('No email activity found for this user.');
            return 0;
        }

        $this->info('Email Activity:');
        $headers = ['ID', 'Type', 'Subject', 'Status', 'Sent At', 'Created At'];
        $rows = [];

        foreach ($emailLogs as $log) {
            $rows[] = [
                $log->id,
                $log->type,
                substr($log->subject, 0, 40) . (strlen($log->subject) > 40 ? '...' : ''),
                $log->status,
                $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : '-',
                $log->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->table($headers, $rows);

        // Summary
        $totalEmails = $emailLogs->count();
        $sentEmails = $emailLogs->where('status', 'sent')->count();
        $failedEmails = $emailLogs->where('status', 'failed')->count();
        $verificationEmails = $emailLogs->where('type', 'verification')->count();

        $this->line('');
        $this->info('Summary:');
        $this->line("Total emails: {$totalEmails}");
        $this->line("Sent: {$sentEmails}");
        $this->line("Failed: {$failedEmails}");
        $this->line("Verification emails: {$verificationEmails}");

        return 0;
    }
}
