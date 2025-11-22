<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheme_id',
        'version',
        'changes_summary',
        'status',
        'is_current',
        'effective_date',
        'expiry_date',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(SchemeUnit::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(SchemeRequirement::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(SchemeDocument::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
