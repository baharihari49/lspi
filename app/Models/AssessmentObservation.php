<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentObservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_unit_id',
        'assessment_criteria_id',
        'observer_id',
        'observation_number',
        'activity_observed',
        'context',
        'observed_at',
        'duration_minutes',
        'location',
        'what_was_observed',
        'performance_indicators',
        'evidence_collected',
        'competency_demonstrated',
        'score',
        'strengths',
        'areas_for_improvement',
        'observer_notes',
        'requires_follow_up',
        'follow_up_notes',
        'metadata',
        'display_order',
    ];

    protected $casts = [
        'observed_at' => 'datetime',
        'score' => 'decimal:2',
        'requires_follow_up' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function assessmentUnit(): BelongsTo
    {
        return $this->belongsTo(AssessmentUnit::class);
    }

    public function assessmentCriteria(): BelongsTo
    {
        return $this->belongsTo(AssessmentCriteria::class);
    }

    public function observer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observer_id');
    }

    // Scopes
    public function scopeRequiresFollowUp($query)
    {
        return $query->where('requires_follow_up', true);
    }

    public function scopeFullyCompetent($query)
    {
        return $query->where('competency_demonstrated', 'fully_competent');
    }

    // Helper methods
    public function isFullyCompetent(): bool
    {
        return $this->competency_demonstrated === 'fully_competent';
    }

    public function requiresFollowUp(): bool
    {
        return $this->requires_follow_up;
    }
}
