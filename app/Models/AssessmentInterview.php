<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentInterview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_unit_id',
        'assessment_criteria_id',
        'interviewer_id',
        'interviewee_id',
        'interview_number',
        'session_title',
        'purpose',
        'conducted_at',
        'duration_minutes',
        'location',
        'interview_method',
        'questions',
        'key_findings',
        'competencies_demonstrated',
        'gaps_identified',
        'overall_assessment',
        'score',
        'interviewer_notes',
        'behavioral_observations',
        'technical_observations',
        'requires_follow_up',
        'follow_up_items',
        'recording_file_path',
        'transcript',
        'metadata',
        'display_order',
    ];

    protected $casts = [
        'conducted_at' => 'datetime',
        'score' => 'decimal:2',
        'requires_follow_up' => 'boolean',
        'questions' => 'array',
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

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function interviewee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewee_id');
    }

    // Scopes
    public function scopeFullySatisfactory($query)
    {
        return $query->where('overall_assessment', 'fully_satisfactory');
    }

    public function scopeRequiresFollowUp($query)
    {
        return $query->where('requires_follow_up', true);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('interview_method', $method);
    }

    // Helper methods
    public function isFullySatisfactory(): bool
    {
        return $this->overall_assessment === 'fully_satisfactory';
    }

    public function requiresFollowUp(): bool
    {
        return $this->requires_follow_up;
    }

    public function hasRecording(): bool
    {
        return !is_null($this->recording_file_path);
    }

    public function hasTranscript(): bool
    {
        return !is_null($this->transcript);
    }
}
