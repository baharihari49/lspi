<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apl02AssessorReview extends Model
{
    use SoftDeletes;

    protected $table = 'apl02_assessor_review';

    protected $fillable = [
        'apl02_unit_id',
        'assessor_id',
        'review_number',
        'review_type',
        'decision',
        'overall_comments',
        'element_assessments',
        'evidence_assessments',
        'validity_score',
        'authenticity_score',
        'currency_score',
        'sufficiency_score',
        'consistency_score',
        'overall_score',
        'recommendations',
        'improvement_areas',
        'additional_evidence_required',
        'next_steps',
        'strengths',
        'weaknesses',
        'requires_interview',
        'requires_demonstration',
        'interview_notes',
        'demonstration_notes',
        'interview_conducted_at',
        'demonstration_conducted_at',
        'review_started_at',
        'review_completed_at',
        'review_duration_minutes',
        'deadline',
        'status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'is_final',
        'is_passed',
        'requires_re_assessment',
        're_assessment_date',
        'metadata',
        'internal_notes',
    ];

    protected $casts = [
        'element_assessments' => 'array',
        'evidence_assessments' => 'array',
        'validity_score' => 'decimal:2',
        'authenticity_score' => 'decimal:2',
        'currency_score' => 'decimal:2',
        'sufficiency_score' => 'decimal:2',
        'consistency_score' => 'decimal:2',
        'overall_score' => 'decimal:2',
        'improvement_areas' => 'array',
        'next_steps' => 'array',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'interview_conducted_at' => 'datetime',
        'demonstration_conducted_at' => 'datetime',
        'review_started_at' => 'datetime',
        'review_completed_at' => 'datetime',
        'deadline' => 'date',
        'verified_at' => 'datetime',
        're_assessment_date' => 'date',
        'metadata' => 'array',
        'requires_interview' => 'boolean',
        'requires_demonstration' => 'boolean',
        'is_final' => 'boolean',
        'is_passed' => 'boolean',
        'requires_re_assessment' => 'boolean',
        'review_duration_minutes' => 'integer',
    ];

    // Relationships
    public function apl02Unit(): BelongsTo
    {
        return $this->belongsTo(Apl02Unit::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeByUnit($query, $unitId)
    {
        return $query->where('apl02_unit_id', $unitId);
    }

    public function scopeByAssessor($query, $assessorId)
    {
        return $query->where('assessor_id', $assessorId);
    }

    public function scopeByReviewType($query, $type)
    {
        return $query->where('review_type', $type);
    }

    public function scopeByDecision($query, $decision)
    {
        return $query->where('decision', $decision);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompetent($query)
    {
        return $query->where('decision', 'competent');
    }

    public function scopeNotYetCompetent($query)
    {
        return $query->where('decision', 'not_yet_competent');
    }

    public function scopePending($query)
    {
        return $query->where('decision', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    public function scopeRequiringReAssessment($query)
    {
        return $query->where('requires_re_assessment', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'submitted', 'approved']);
    }

    // Accessors
    public function getReviewTypeLabelAttribute(): string
    {
        return match($this->review_type) {
            'initial_review' => 'Initial Review',
            'verification' => 'Verification',
            'validation' => 'Validation',
            'final_assessment' => 'Final Assessment',
            're_assessment' => 'Re-Assessment',
            default => ucfirst($this->review_type),
        };
    }

    public function getDecisionLabelAttribute(): string
    {
        return match($this->decision) {
            'competent' => 'Competent',
            'not_yet_competent' => 'Not Yet Competent',
            'pending' => 'Pending',
            'requires_more_evidence' => 'Requires More Evidence',
            'requires_demonstration' => 'Requires Demonstration',
            'deferred' => 'Deferred',
            default => ucfirst($this->decision),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'submitted' => 'Submitted',
            'approved' => 'Approved',
            'revision_required' => 'Revision Required',
            default => ucfirst($this->status),
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->deadline || in_array($this->status, ['completed', 'submitted', 'approved'])) {
            return false;
        }

        return $this->deadline->isPast();
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsCompetentAttribute(): bool
    {
        return $this->decision === 'competent';
    }

    public function getReviewDurationFormattedAttribute(): ?string
    {
        if (!$this->review_duration_minutes) {
            return null;
        }

        $hours = floor($this->review_duration_minutes / 60);
        $minutes = $this->review_duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    // Helper Methods
    public function generateReviewNumber(): string
    {
        $year = date('Y');

        // Include soft deleted records to avoid duplicate numbers
        $lastReview = static::withTrashed()
            ->where('review_number', 'like', "REV-APL02-{$year}-%")
            ->orderBy('review_number', 'desc')
            ->first();

        $newNumber = $lastReview
            ? ((int)substr($lastReview->review_number, -4) + 1)
            : 1;

        return 'REV-APL02-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function startReview(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        $this->status = 'in_progress';
        $this->review_started_at = now();
        return $this->save();
    }

    public function completeReview($decision, $scores = []): bool
    {
        $this->decision = $decision;
        $this->status = 'completed';
        $this->review_completed_at = now();

        if ($this->review_started_at) {
            $this->review_duration_minutes = $this->review_started_at->diffInMinutes($this->review_completed_at);
        }

        // Set VATUK scores
        if (isset($scores['validity'])) {
            $this->validity_score = $scores['validity'];
        }
        if (isset($scores['authenticity'])) {
            $this->authenticity_score = $scores['authenticity'];
        }
        if (isset($scores['currency'])) {
            $this->currency_score = $scores['currency'];
        }
        if (isset($scores['sufficiency'])) {
            $this->sufficiency_score = $scores['sufficiency'];
        }
        if (isset($scores['consistency'])) {
            $this->consistency_score = $scores['consistency'];
        }

        // Calculate overall score
        $this->calculateOverallScore();

        // Set is_passed based on decision
        $this->is_passed = $decision === 'competent';

        return $this->save();
    }

    public function calculateOverallScore(): void
    {
        $scores = array_filter([
            $this->validity_score,
            $this->authenticity_score,
            $this->currency_score,
            $this->sufficiency_score,
            $this->consistency_score,
        ]);

        if (!empty($scores)) {
            $this->overall_score = round(array_sum($scores) / count($scores), 2);
        }
    }

    public function verify($verifierId, $notes = null): bool
    {
        $this->verified_by = $verifierId;
        $this->verified_at = now();
        $this->verification_notes = $notes;
        $this->status = 'approved';
        return $this->save();
    }

    public function requireRevision($notes): bool
    {
        $this->status = 'revision_required';
        $this->verification_notes = $notes;
        return $this->save();
    }

    public function markAsFinal(): bool
    {
        $this->is_final = true;
        return $this->save();
    }

    public function scheduleReAssessment($date, $notes = null): bool
    {
        $this->requires_re_assessment = true;
        $this->re_assessment_date = $date;
        $this->recommendations = $notes;
        return $this->save();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if (!$review->review_number) {
                $review->review_number = $review->generateReviewNumber();
            }
        });

        static::updated(function ($review) {
            if ($review->isDirty(['validity_score', 'authenticity_score', 'currency_score', 'sufficiency_score', 'consistency_score'])) {
                $review->calculateOverallScore();
            }
        });
    }
}
