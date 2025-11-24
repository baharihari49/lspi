<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'bank_name',
        'account_number',
        'account_holder_name',
        'provider_name',
        'gateway_code',
        'gateway_config',
        'admin_fee_percentage',
        'admin_fee_fixed',
        'min_amount',
        'max_amount',
        'icon_path',
        'logo_url',
        'instructions',
        'display_order',
        'is_active',
        'requires_manual_verification',
    ];

    protected $casts = [
        'gateway_config' => 'array',
        'admin_fee_percentage' => 'decimal:2',
        'admin_fee_fixed' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_manual_verification' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the payments that use this payment method.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope: Only active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Only inactive payment methods
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if this payment method requires manual verification
     */
    public function requiresManualVerification(): bool
    {
        return $this->requires_manual_verification;
    }

    /**
     * Check if this payment method uses a payment gateway
     */
    public function usesGateway(): bool
    {
        return !empty($this->gateway_code);
    }

    /**
     * Calculate admin fee for a given amount
     */
    public function calculateAdminFee(float $amount): float
    {
        $percentageFee = $amount * ($this->admin_fee_percentage / 100);
        $totalFee = $percentageFee + $this->admin_fee_fixed;

        return round($totalFee, 2);
    }

    /**
     * Calculate total amount including admin fee
     */
    public function calculateTotalWithFee(float $amount): float
    {
        return $amount + $this->calculateAdminFee($amount);
    }

    /**
     * Check if amount is within allowed limits
     */
    public function isAmountValid(float $amount): bool
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted account information
     */
    public function getAccountInfo(): ?string
    {
        if ($this->bank_name && $this->account_number) {
            return "{$this->bank_name} - {$this->account_number} a.n {$this->account_holder_name}";
        }

        return null;
    }

    /**
     * Get payment method icon
     */
    public function getIcon(): ?string
    {
        return $this->icon_path ?? $this->logo_url;
    }
}
