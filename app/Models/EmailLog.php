<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'to_email',
        'subject',
        'type',
        'status',
        'message_id',
        'metadata',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
    ];

    public function scopeVerificationEmails($query)
    {
        return $query->where('type', 'verification');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForEmail($query, $email)
    {
        return $query->where('to_email', $email);
    }
}
