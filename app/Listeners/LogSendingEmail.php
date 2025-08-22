<?php

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class LogSendingEmail
{
    public function handle(MessageSending $event): void
    {
        try {
            // Simple logging approach that works across Laravel versions
            $subject = 'Email Sending';
            $toEmails = [];
            
            // Try to extract email from any available data
            if (isset($event->message)) {
                $message = $event->message;
                if (method_exists($message, 'getSubject')) {
                    $subject = $message->getSubject();
                }
                if (method_exists($message, 'getTo')) {
                    $to = $message->getTo();
                    if (is_array($to)) {
                        // Extract email addresses properly
                        foreach ($to as $email => $name) {
                            if (is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $toEmails[] = $email;
                            } elseif (is_numeric($email) && is_string($name) && filter_var($name, FILTER_VALIDATE_EMAIL)) {
                                $toEmails[] = $name;
                            }
                        }
                    }
                }
            }
            
            // Determine email type
            $type = 'general';
            if (str_contains(strtolower($subject), 'verification') || str_contains(strtolower($subject), 'verify')) {
                $type = 'verification';
            }
            
            // Create log entries
            foreach ($toEmails as $email) {
                EmailLog::create([
                    'to_email' => $email,
                    'subject' => $subject,
                    'type' => $type,
                    'status' => 'sending',
                    'metadata' => [
                        'timestamp' => now()->toDateTimeString(),
                    ],
                ]);
            }
            
            Log::info('Email sending logged', [
                'to' => $toEmails,
                'subject' => $subject,
                'type' => $type,
                'timestamp' => now()->toDateTimeString(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in LogSendingEmail listener', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
