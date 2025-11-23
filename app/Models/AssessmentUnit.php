<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_id',
        'scheme_unit_id',
        'assessor_id',
        'unit_code',
        'unit_title',
        'unit_description',
        'assessment_method',
        'status',
        'score',
        'elements_passed',
        'total_elements',
        'completion_percentage',
        'result',
        'started_at',
        'completed_at',
        'duration_minutes',
        'assessor_notes',
        'feedback',
        'strengths',
        'weaknesses',
        'recommendations',
        'metadata',
        'display_order',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'completion_percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'recommendations' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function schemeUnit(): BelongsTo
    {
        return $this->belongsTo(SchemeUnit::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(AssessmentCriteria::class);
    }

    public function observations(): HasMany
    {
        return $this->hasMany(AssessmentObservation::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(AssessmentInterview::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(AssessmentFeedback::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompetent($query)
    {
        return $query->where('result', 'competent');
    }

    public function scopeNotYetCompetent($query)
    {
        return $query->where('result', 'not_yet_competent');
    }

    // Helper methods
    public function isCompetent(): bool
    {
        return $this->result === 'competent';
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['completed', 'competent', 'not_yet_competent']);
    }

    public function getCompletionPercentage(): float
    {
        if ($this->total_elements == 0) {
            return 0;
        }
        return ($this->elements_passed / $this->total_elements) * 100;
    }
}
