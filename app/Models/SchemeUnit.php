<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheme_version_id',
        'code',
        'title',
        'description',
        'unit_type',
        'credit_hours',
        'order',
        'is_mandatory',
    ];

    protected $casts = [
        'credit_hours' => 'integer',
        'order' => 'integer',
        'is_mandatory' => 'boolean',
    ];

    // Relationships
    public function schemeVersion(): BelongsTo
    {
        return $this->belongsTo(SchemeVersion::class);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(SchemeElement::class)->orderBy('order');
    }

    // Scopes
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_mandatory', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('unit_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
