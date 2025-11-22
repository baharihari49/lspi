<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeElement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheme_unit_id',
        'code',
        'title',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // Relationships
    public function schemeUnit(): BelongsTo
    {
        return $this->belongsTo(SchemeUnit::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(SchemeCriterion::class)->orderBy('order');
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
