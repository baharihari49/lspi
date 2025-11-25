<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'channel',
        'priority',
        'notifiable_type',
        'notifiable_id',
        'title',
        'message',
        'data',
        'action_url',
        'action_text',
        'relatable_type',
        'relatable_id',
        'status',
        'read_at',
        'sent_at',
        'failed_at',
        'failure_reason',
        'retry_count',
        'recipient_email',
        'recipient_phone',
        'recipient_name',
        'notification_id',
        'provider_response',
        'scheduled_at',
    ];

    protected $casts = [
        'data' => 'array',
        'provider_response' => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    /**
     * Get the notifiable entity (User, etc)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the related entity (Assessment, Payment, etc)
     */
    public function relatable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: Only unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope: Only read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope: Only sent notifications
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Only pending notifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only failed notifications
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Filter by channel
     */
    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Scope: Filter by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter by priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Scheduled notifications
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'read_at' => now(),
            'status' => 'read',
        ]);
    }

    /**
     * Mark notification as sent
     */
    public function markAsSent(): bool
    {
        return $this->update([
            'sent_at' => now(),
            'status' => 'sent',
        ]);
    }

    /**
     * Mark notification as failed
     */
    public function markAsFailed(string $reason = null): bool
    {
        return $this->update([
            'failed_at' => now(),
            'status' => 'failed',
            'failure_reason' => $reason,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Check if notification is sent
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if notification is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if notification is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if notification is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->scheduled_at !== null && $this->scheduled_at->isFuture();
    }

    /**
     * Check if notification can be retried
     */
    public function canRetry(): bool
    {
        return $this->isFailed() && $this->retry_count < 3;
    }
}
