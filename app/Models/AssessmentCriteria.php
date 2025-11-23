<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentCriteria extends Model
{
    use SoftDeletes;

    protected $table = 'assessment_criteria';

    protected $fillable = [
        'assessment_unit_id',
        'scheme_element_id',
        'element_code',
        'element_title',
        'assessment_method',
        'result',
        'score',
        'is_critical',
        'evidence_observed',
        'assessor_notes',
        'feedback',
        'assessed_at',
        'assessed_by',
        'metadata',
        'display_order',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'is_critical' => 'boolean',
        'assessed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function assessmentUnit(): BelongsTo
    {
        return $this->belongsTo(AssessmentUnit::class);
    }

    public function schemeElement(): BelongsTo
    {
        return $this->belongsTo(SchemeElement::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    // Scopes
    public function scopeCompetent($query)
    {
        return $query->where('result', 'competent');
    }

    public function scopeNotYetCompetent($query)
    {
        return $query->where('result', 'not_yet_competent');
    }

    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    public function scopeAssessed($query)
    {
        return $query->whereNotNull('assessed_at');
    }

    // Helper methods
    public function isCompetent(): bool
    {
        return $this->result === 'competent';
    }

    public function isCritical(): bool
    {
        return $this->is_critical;
    }

    public function isAssessed(): bool
    {
        return !is_null($this->assessed_at);
    }
}
