<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentStatusHistory extends Model
{
    // No timestamps in this table, we use 'changed_at' instead
    public $timestamps = false;

    protected $table = 'payment_status_history';

    protected $fillable = [
        'payment_id',
        'from_status',
        'to_status',
        'change_reason',
        'changed_by',
        'changed_by_name',
        'notes',
        'metadata',
        'ip_address',
        'user_agent',
        'changed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'changed_at' => 'datetime',
    ];

    /**
     * Get the payment that this history record belongs to.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the user who made the change.
     */
    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Scope: Filter by payment
     */
    public function scopeForPayment($query, int $paymentId)
    {
        return $query->where('payment_id', $paymentId);
    }

    /**
     * Scope: Filter by status change
     */
    public function scopeByStatusChange($query, ?string $fromStatus = null, ?string $toStatus = null)
    {
        if ($fromStatus) {
            $query->where('from_status', $fromStatus);
        }

        if ($toStatus) {
            $query->where('to_status', $toStatus);
        }

        return $query;
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('changed_by', $userId);
    }

    /**
     * Scope: Recent changes first
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('changed_at', 'desc');
    }

    /**
     * Scope: Changes within date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('changed_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted status change description
     */
    public function getChangeDescription(): string
    {
        $from = $this->getStatusLabel($this->from_status);
        $to = $this->getStatusLabel($this->to_status);

        return "Status berubah dari {$from} ke {$to}";
    }

    /**
     * Get formatted status label
     */
    protected function getStatusLabel(?string $status): string
    {
        if (!$status) {
            return 'N/A';
        }

        return match($status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'partial' => 'Dibayar Sebagian',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            default => ucfirst($status),
        };
    }

    /**
     * Get who made the change (user name or system)
     */
    public function getChangedByName(): string
    {
        if ($this->changed_by && $this->changedByUser) {
            return $this->changedByUser->name;
        }

        return $this->changed_by_name ?? 'System';
    }

    /**
     * Check if change was made by system
     */
    public function isSystemChange(): bool
    {
        return is_null($this->changed_by);
    }

    /**
     * Check if change was made by user
     */
    public function isUserChange(): bool
    {
        return !is_null($this->changed_by);
    }

    /**
     * Get formatted change time (relative)
     */
    public function getTimeAgo(): string
    {
        return $this->changed_at->diffForHumans();
    }

    /**
     * Get change reason with fallback
     */
    public function getChangeReason(): string
    {
        return $this->change_reason ?? 'Tidak ada keterangan';
    }

    /**
     * Check if this is a status upgrade (improvement)
     */
    public function isUpgrade(): bool
    {
        $statusOrder = [
            'pending' => 1,
            'partial' => 2,
            'paid' => 3,
        ];

        $fromOrder = $statusOrder[$this->from_status] ?? 0;
        $toOrder = $statusOrder[$this->to_status] ?? 0;

        return $toOrder > $fromOrder;
    }

    /**
     * Check if this is a status downgrade
     */
    public function isDowngrade(): bool
    {
        $badStatuses = ['cancelled', 'failed', 'refunded'];

        return in_array($this->to_status, $badStatuses);
    }

    /**
     * Get icon for status change
     */
    public function getIcon(): string
    {
        if ($this->isUpgrade()) {
            return 'trending_up';
        }

        if ($this->isDowngrade()) {
            return 'trending_down';
        }

        return 'swap_horiz';
    }

    /**
     * Get color for status change
     */
    public function getColor(): string
    {
        if ($this->isUpgrade()) {
            return 'green';
        }

        if ($this->isDowngrade()) {
            return 'red';
        }

        return 'blue';
    }

    /**
     * Get formatted metadata
     */
    public function getFormattedMetadata(): ?string
    {
        if (!$this->metadata || empty($this->metadata)) {
            return null;
        }

        return json_encode($this->metadata, JSON_PRETTY_PRINT);
    }

    /**
     * Create a new status history record
     */
    public static function logChange(
        int $paymentId,
        ?string $fromStatus,
        string $toStatus,
        ?string $reason = null,
        ?int $changedBy = null,
        ?string $changedByName = null,
        ?array $metadata = null
    ): self {
        return static::create([
            'payment_id' => $paymentId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'change_reason' => $reason,
            'changed_by' => $changedBy,
            'changed_by_name' => $changedByName ?? ($changedBy ? null : 'System'),
            'notes' => null,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changed_at' => now(),
        ]);
    }

    /**
     * Get timeline for a payment
     */
    public static function getTimeline(int $paymentId): array
    {
        return static::forPayment($paymentId)
            ->recent()
            ->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'from_status' => $history->from_status,
                    'to_status' => $history->to_status,
                    'description' => $history->getChangeDescription(),
                    'reason' => $history->getChangeReason(),
                    'changed_by' => $history->getChangedByName(),
                    'time' => $history->changed_at->format('d M Y H:i'),
                    'time_ago' => $history->getTimeAgo(),
                    'icon' => $history->getIcon(),
                    'color' => $history->getColor(),
                    'is_system' => $history->isSystemChange(),
                ];
            })
            ->toArray();
    }
}
