<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_number',
        'invoice_number',
        'payer_id',
        'payer_type',
        'payer_name',
        'payer_email',
        'payer_phone',
        'payable_type',
        'payable_id',
        'event_id',
        'assessment_id',
        'payment_method_id',
        'subtotal',
        'admin_fee',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'discount_code',
        'discount_percentage',
        'status',
        'invoice_date',
        'due_date',
        'paid_at',
        'cancelled_at',
        'refunded_at',
        'transaction_id',
        'payment_gateway',
        'gateway_response',
        'payment_channel',
        'payment_proof_path',
        'verified_at',
        'verified_by',
        'notes',
        'internal_notes',
        'metadata',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
        'verified_at' => 'datetime',
        'gateway_response' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the payer (user) who made this payment.
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Get the payment method used for this payment.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the event associated with this payment (if applicable).
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the assessment associated with this payment (if applicable).
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the user who verified this payment.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get all payment items (line items) for this payment.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PaymentItem::class);
    }

    /**
     * Get all status history records for this payment.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(PaymentStatusHistory::class)->orderBy('changed_at', 'desc');
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Only pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Only paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Overdue payments
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }

    /**
     * Scope: Filter by payer
     */
    public function scopeByPayer($query, int $payerId)
    {
        return $query->where('payer_id', $payerId);
    }

    /**
     * Scope: Filter by payable (polymorphic)
     */
    public function scopeByPayable($query, string $type, int $id)
    {
        return $query->where('payable_type', $type)
            ->where('payable_id', $id);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is partially paid
     */
    public function isPartiallyPaid(): bool
    {
        return $this->status === 'partial';
    }

    /**
     * Check if payment is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Check if payment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->isPending() && $this->due_date < now();
    }

    /**
     * Check if payment is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    /**
     * Check if payment has proof uploaded
     */
    public function hasProof(): bool
    {
        return !is_null($this->payment_proof_path);
    }

    /**
     * Get payment proof URL
     */
    public function getProofUrl(): ?string
    {
        if ($this->payment_proof_path) {
            return Storage::url($this->payment_proof_path);
        }

        return null;
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(?string $transactionId = null, ?array $gatewayResponse = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'paid_amount' => $this->total_amount,
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'gateway_response' => $gatewayResponse ?? $this->gateway_response,
        ]);

        // Log status change
        $this->logStatusChange('pending', 'paid', 'Payment received');
    }

    /**
     * Mark payment as verified
     */
    public function markAsVerified(int $verifiedBy): void
    {
        $this->update([
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
        ]);
    }

    /**
     * Cancel payment
     */
    public function cancel(?string $reason = null): void
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Log status change
        $this->logStatusChange($oldStatus, 'cancelled', $reason ?? 'Payment cancelled');
    }

    /**
     * Refund payment
     */
    public function refund(?string $reason = null): void
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'refunded',
            'refunded_at' => now(),
        ]);

        // Log status change
        $this->logStatusChange($oldStatus, 'refunded', $reason ?? 'Payment refunded');
    }

    /**
     * Log status change to payment_status_history
     */
    protected function logStatusChange(string $fromStatus, string $toStatus, ?string $reason = null): void
    {
        $this->statusHistory()->create([
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'change_reason' => $reason,
            'changed_by' => auth()->id(),
            'changed_by_name' => auth()->user()?->name ?? 'System',
            'changed_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Generate unique payment number
     */
    public static function generatePaymentNumber(): string
    {
        $prefix = 'INV';
        $year = now()->year;
        $month = now()->format('m');

        // Get last payment number for this month
        $lastPayment = static::whereYear('created_at', $year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastPayment ? ((int) substr($lastPayment->payment_number, -4)) + 1 : 1;

        return sprintf('%s-%d%s-%04d', $prefix, $year, $month, $sequence);
    }

    /**
     * Get formatted status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'partial' => 'Dibayar Sebagian',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'partial' => 'blue',
            'failed' => 'red',
            'cancelled' => 'gray',
            'refunded' => 'purple',
            default => 'gray',
        };
    }
}
