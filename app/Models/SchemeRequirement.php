<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeRequirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheme_version_id',
        'requirement_type',
        'title',
        'description',
        'is_mandatory',
        'notes',
        'order',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'order' => 'integer',
    ];

    // Relationships
    public function schemeVersion(): BelongsTo
    {
        return $this->belongsTo(SchemeVersion::class);
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
        return $query->where('requirement_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
