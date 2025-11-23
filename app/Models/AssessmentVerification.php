<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentVerification extends Model
{
    use SoftDeletes;

    protected $table = 'assessment_verification';

    protected $fillable = [
        'assessment_id',
        'assessment_unit_id',
        'verifier_id',
        'verification_number',
        'verification_level',
        'verification_type',
        'checklist',
        'verification_status',
        'verification_result',
        'findings',
        'strengths',
        'concerns',
        'areas_for_improvement',
        'meets_standards',
        'evidence_sufficient',
        'assessment_fair',
        'documentation_complete',
        'required_actions',
        'recommendations',
        'verified_at',
        'verification_duration_minutes',
        'verifier_notes',
        'assessor_response',
        'metadata',
    ];

    protected $casts = [
        'checklist' => 'array',
        'required_actions' => 'array',
        'recommendations' => 'array',
        'meets_standards' => 'boolean',
        'evidence_sufficient' => 'boolean',
        'assessment_fair' => 'boolean',
        'documentation_complete' => 'boolean',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function assessmentUnit(): BelongsTo
    {
        return $this->belongsTo(AssessmentUnit::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('verification_level', $level);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('verification_type', $type);
    }

    public function scopeSatisfactory($query)
    {
        return $query->where('verification_result', 'satisfactory');
    }

    // Helper methods
    public function isApproved(): bool
    {
        return $this->verification_status === 'approved';
    }

    public function isSatisfactory(): bool
    {
        return $this->verification_result === 'satisfactory';
    }

    public function meetsAllStandards(): bool
    {
        return $this->meets_standards
            && $this->evidence_sufficient
            && $this->assessment_fair
            && $this->documentation_complete;
    }
}
