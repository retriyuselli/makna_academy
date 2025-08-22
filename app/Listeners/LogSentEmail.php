<?php

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentEmail
{
    public function handle(MessageSent $event): void
    {
        try {
            // Simple approach for Laravel 12
            Log::info('Email sent event triggered', [
                'event_class' => get_class($event),
                'timestamp' => now()->toDateTimeString(),
            ]);
            
            // Mark recent sending emails as sent
            $recentSending = EmailLog::where('status', 'sending')
                ->where('created_at', '>=', now()->subMinutes(5))
                ->get();
                
            foreach ($recentSending as $emailLog) {
                $emailLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
            
            Log::info('Email sent successfully', [
                'updated_logs' => $recentSending->count(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in LogSentEmail listener', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
