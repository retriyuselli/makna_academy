<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MonitorEmailActivity extends Command
{
    protected $signature = 'email:monitor {--lines=50 : Number of lines to show}';
    protected $description = 'Monitor email activity from Laravel logs';

    public function handle()
    {
        $lines = $this->option('lines');
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            $this->error('Log file not found: ' . $logFile);
            return 1;
        }

        $this->info('Monitoring email activity (last ' . $lines . ' lines)...');
        $this->line('');

        // Read and filter log entries
        $content = File::get($logFile);
        $logLines = explode("\n", $content);
        
        // Filter lines containing email-related content
        $emailLines = array_filter($logLines, function($line) {
            return stripos($line, 'email') !== false || 
                   stripos($line, 'mail') !== false ||
                   stripos($line, 'verification') !== false ||
                   stripos($line, 'MessageSent') !== false ||
                   stripos($line, 'MessageSending') !== false;
        });

        // Get last N lines
        $recentEmailLines = array_slice($emailLines, -$lines);

        if (empty($recentEmailLines)) {
            $this->warn('No email activity found in logs.');
            return 0;
        }

        foreach ($recentEmailLines as $line) {
            if (stripos($line, 'error') !== false || stripos($line, 'failed') !== false) {
                $this->error($line);
            } elseif (stripos($line, 'sent') !== false) {
                $this->info($line);
            } else {
                $this->line($line);
            }
        }

        return 0;
    }
}
