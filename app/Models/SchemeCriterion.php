<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeCriterion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheme_element_id',
        'code',
        'description',
        'evidence_guide',
        'assessment_method',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // Relationships
    public function schemeElement(): BelongsTo
    {
        return $this->belongsTo(SchemeElement::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByAssessmentMethod($query, $method)
    {
        return $query->where('assessment_method', $method);
    }
}
