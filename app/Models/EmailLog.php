<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'message_id',
        'subject',
        'from_email',
        'from_name',
        'to_email',
        'to_name',
        'cc_emails',
        'bcc_emails',
        'reply_to',
        'body_html',
        'body_text',
        'attachments',
        'email_type',
        'template_name',
        'template_data',
        'emailable_type',
        'emailable_id',
        'sent_by',
        'sent_by_name',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'failed_at',
        'open_count',
        'click_count',
        'last_opened_at',
        'last_clicked_at',
        'provider',
        'provider_response',
        'error_message',
        'retry_count',
        'headers',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'attachments' => 'array',
        'template_data' => 'array',
        'provider_response' => 'array',
        'headers' => 'array',
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'failed_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'open_count' => 'integer',
        'click_count' => 'integer',
        'retry_count' => 'integer',
    ];

    /**
     * Get the emailable entity (User, Assessment, Payment, etc)
     */
    public function emailable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who sent the email
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Scope: Only sent emails
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Only pending emails
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only delivered emails
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope: Only bounced emails
     */
    public function scopeBounced($query)
    {
        return $query->where('status', 'bounced');
    }

    /**
     * Scope: Only failed emails
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Only opened emails
     */
    public function scopeOpened($query)
    {
        return $query->whereNotNull('opened_at');
    }

    /**
     * Scope: Only clicked emails
     */
    public function scopeClicked($query)
    {
        return $query->whereNotNull('clicked_at');
    }

    /**
     * Scope: Filter by email type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('email_type', $type);
    }

    /**
     * Scope: Filter by provider
     */
    public function scopeByProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope: Filter by recipient
     */
    public function scopeToRecipient($query, string $email)
    {
        return $query->where('to_email', $email);
    }

    /**
     * Mark email as sent
     */
    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark email as delivered
     */
    public function markAsDelivered(): bool
    {
        return $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark email as opened
     */
    public function markAsOpened(): bool
    {
        return $this->update([
            'opened_at' => $this->opened_at ?? now(),
            'last_opened_at' => now(),
            'open_count' => $this->open_count + 1,
            'status' => 'opened',
        ]);
    }

    /**
     * Mark email as clicked
     */
    public function markAsClicked(): bool
    {
        return $this->update([
            'clicked_at' => $this->clicked_at ?? now(),
            'last_clicked_at' => now(),
            'click_count' => $this->click_count + 1,
            'status' => 'clicked',
        ]);
    }

    /**
     * Mark email as bounced
     */
    public function markAsBounced(string $reason = null): bool
    {
        return $this->update([
            'status' => 'bounced',
            'bounced_at' => now(),
            'error_message' => $reason,
        ]);
    }

    /**
     * Mark email as failed
     */
    public function markAsFailed(string $reason = null): bool
    {
        return $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'error_message' => $reason,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    /**
     * Check if email is sent
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if email is delivered
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if email is opened
     */
    public function isOpened(): bool
    {
        return $this->opened_at !== null;
    }

    /**
     * Check if email is clicked
     */
    public function isClicked(): bool
    {
        return $this->clicked_at !== null;
    }

    /**
     * Check if email is bounced
     */
    public function isBounced(): bool
    {
        return $this->status === 'bounced';
    }

    /**
     * Check if email is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if email can be retried
     */
    public function canRetry(): bool
    {
        return $this->isFailed() && $this->retry_count < 3;
    }

    /**
     * Get open rate percentage
     */
    public function getOpenRateAttribute(): float
    {
        return $this->isOpened() ? 100 : 0;
    }

    /**
     * Get click rate percentage
     */
    public function getClickRateAttribute(): float
    {
        return $this->isClicked() ? 100 : 0;
    }
}
