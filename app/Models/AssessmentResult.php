<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_id',
        'assessee_id',
        'scheme_id',
        'result_number',
        'certificate_number',
        'final_result',
        'overall_score',
        'units_assessed',
        'units_competent',
        'units_not_yet_competent',
        'total_criteria',
        'criteria_met',
        'criteria_percentage',
        'critical_criteria_total',
        'critical_criteria_met',
        'all_critical_criteria_met',
        'unit_results',
        'executive_summary',
        'key_strengths',
        'development_areas',
        'overall_performance_notes',
        'documents_submitted',
        'observations_conducted',
        'interviews_conducted',
        'evidence_summary',
        'recommendations',
        'next_steps',
        'reassessment_plan',
        'certification_date',
        'certification_expiry_date',
        'certification_level',
        'certificate_issued',
        'certificate_issued_at',
        'lead_assessor_id',
        'contributing_assessors',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'is_published',
        'published_at',
        'published_by',
        'assessee_notified',
        'assessee_notified_at',
        'is_valid',
        'invalidation_reason',
        'invalidated_at',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'criteria_percentage' => 'decimal:2',
        'all_critical_criteria_met' => 'boolean',
        'unit_results' => 'array',
        'key_strengths' => 'array',
        'development_areas' => 'array',
        'recommendations' => 'array',
        'contributing_assessors' => 'array',
        'certification_date' => 'date',
        'certification_expiry_date' => 'date',
        'certificate_issued' => 'boolean',
        'certificate_issued_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'assessee_notified' => 'boolean',
        'assessee_notified_at' => 'datetime',
        'is_valid' => 'boolean',
        'invalidated_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function leadAssessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_assessor_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ResultApproval::class);
    }

    // Scopes
    public function scopeCompetent($query)
    {
        return $query->where('final_result', 'competent');
    }

    public function scopeNotYetCompetent($query)
    {
        return $query->where('final_result', 'not_yet_competent');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeValid($query)
    {
        return $query->where('is_valid', true);
    }

    public function scopeCertified($query)
    {
        return $query->where('certificate_issued', true);
    }

    public function scopeByAssessee($query, $assesseeId)
    {
        return $query->where('assessee_id', $assesseeId);
    }

    public function scopeByScheme($query, $schemeId)
    {
        return $query->where('scheme_id', $schemeId);
    }

    // Helper methods
    public function isCompetent(): bool
    {
        return $this->final_result === 'competent';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function isValid(): bool
    {
        return $this->is_valid;
    }

    public function isCertified(): bool
    {
        return $this->certificate_issued;
    }

    public function hasAssesseeBeenNotified(): bool
    {
        return $this->assessee_notified;
    }

    public function getCriteriaPassRate(): float
    {
        if ($this->total_criteria == 0) {
            return 0;
        }
        return ($this->criteria_met / $this->total_criteria) * 100;
    }

    public function getCriticalCriteriaPassRate(): float
    {
        if ($this->critical_criteria_total == 0) {
            return 0;
        }
        return ($this->critical_criteria_met / $this->critical_criteria_total) * 100;
    }

    public function getResultBadgeColor(): string
    {
        return match($this->final_result) {
            'competent' => 'green',
            'not_yet_competent' => 'red',
            'requires_reassessment' => 'orange',
            default => 'gray',
        };
    }

    public function getApprovalBadgeColor(): string
    {
        return match($this->approval_status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            'requires_revision' => 'orange',
            default => 'gray',
        };
    }
}
