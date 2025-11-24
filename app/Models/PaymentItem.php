<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentItem extends Model
{
    protected $fillable = [
        'payment_id',
        'item_type',
        'item_code',
        'item_name',
        'description',
        'related_type',
        'related_id',
        'unit_price',
        'quantity',
        'discount_amount',
        'tax_amount',
        'subtotal',
        'total',
        'tax_type',
        'tax_percentage',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the payment that owns this item.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Scope: Filter by item type
     */
    public function scopeByItemType($query, string $itemType)
    {
        return $query->where('item_type', $itemType);
    }

    /**
     * Scope: Filter by related entity
     */
    public function scopeByRelated($query, string $relatedType, int $relatedId)
    {
        return $query->where('related_type', $relatedType)
            ->where('related_id', $relatedId);
    }

    /**
     * Calculate subtotal (unit_price * quantity)
     */
    public function calculateSubtotal(): float
    {
        return round($this->unit_price * $this->quantity, 2);
    }

    /**
     * Calculate tax amount based on percentage
     */
    public function calculateTax(?float $amount = null): float
    {
        $baseAmount = $amount ?? $this->subtotal;

        if ($this->tax_percentage > 0) {
            return round($baseAmount * ($this->tax_percentage / 100), 2);
        }

        return 0;
    }

    /**
     * Calculate total (subtotal - discount + tax)
     */
    public function calculateTotal(): float
    {
        $subtotal = $this->calculateSubtotal();
        $taxAmount = $this->calculateTax($subtotal);

        return round($subtotal - $this->discount_amount + $taxAmount, 2);
    }

    /**
     * Recalculate and update amounts
     */
    public function recalculate(): void
    {
        $this->subtotal = $this->calculateSubtotal();
        $this->tax_amount = $this->calculateTax($this->subtotal);
        $this->total = $this->calculateTotal();
        $this->save();
    }

    /**
     * Get formatted item type label
     */
    public function getItemTypeLabel(): string
    {
        return match($this->item_type) {
            'event_fee' => 'Biaya Event',
            'assessment_fee' => 'Biaya Asesmen',
            'certificate_fee' => 'Biaya Sertifikat',
            'renewal_fee' => 'Biaya Perpanjangan',
            'registration_fee' => 'Biaya Pendaftaran',
            'material_fee' => 'Biaya Material',
            'accommodation_fee' => 'Biaya Akomodasi',
            'transportation_fee' => 'Biaya Transportasi',
            'other' => 'Lainnya',
            default => ucfirst(str_replace('_', ' ', $this->item_type)),
        };
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPrice(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotal(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotal(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Get formatted discount amount
     */
    public function getFormattedDiscount(): string
    {
        if ($this->discount_amount > 0) {
            return '- Rp ' . number_format($this->discount_amount, 0, ',', '.');
        }

        return '-';
    }

    /**
     * Get formatted tax amount
     */
    public function getFormattedTax(): string
    {
        if ($this->tax_amount > 0) {
            $percentage = $this->tax_percentage ? " ({$this->tax_percentage}%)" : '';
            return 'Rp ' . number_format($this->tax_amount, 0, ',', '.') . $percentage;
        }

        return '-';
    }

    /**
     * Check if item has discount
     */
    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0;
    }

    /**
     * Check if item has tax
     */
    public function hasTax(): bool
    {
        return $this->tax_amount > 0;
    }

    /**
     * Get related entity (polymorphic-like)
     */
    public function getRelatedEntity()
    {
        if (!$this->related_type || !$this->related_id) {
            return null;
        }

        // Map related types to model classes
        $modelMap = [
            'event' => Event::class,
            'scheme' => Scheme::class,
            'certificate' => Certificate::class,
            'assessment' => Assessment::class,
        ];

        $modelClass = $modelMap[$this->related_type] ?? null;

        if ($modelClass && class_exists($modelClass)) {
            return $modelClass::find($this->related_id);
        }

        return null;
    }

    /**
     * Create a payment item with automatic calculation
     */
    public static function createWithCalculation(array $data): self
    {
        $item = new static($data);

        // Calculate amounts
        $item->subtotal = $item->calculateSubtotal();
        $item->tax_amount = $item->calculateTax($item->subtotal);
        $item->total = $item->calculateTotal();

        $item->save();

        return $item;
    }

    /**
     * Update item and recalculate
     */
    public function updateWithCalculation(array $data): bool
    {
        $this->fill($data);

        // Recalculate amounts
        $this->subtotal = $this->calculateSubtotal();
        $this->tax_amount = $this->calculateTax($this->subtotal);
        $this->total = $this->calculateTotal();

        return $this->save();
    }
}
