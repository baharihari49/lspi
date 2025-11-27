<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apl02Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessee_id',
        'scheme_id',
        'scheme_unit_id',
        'event_id',
        'apl01_form_id',
        'unit_code',
        'unit_title',
        'unit_description',
        'status',
        'total_evidence',
        'completion_percentage',
        'assessor_id',
        'assigned_at',
        'started_at',
        'completed_at',
        'assessment_result',
        'score',
        'assessment_notes',
        'assessment_feedback',
        'recommendations',
        'submitted_at',
        'submitted_by',
        'is_locked',
        'locked_at',
        'metadata',
        'auto_generated',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'locked_at' => 'datetime',
        'assessment_feedback' => 'array',
        'metadata' => 'array',
        'completion_percentage' => 'decimal:2',
        'score' => 'decimal:2',
        'is_locked' => 'boolean',
        'auto_generated' => 'boolean',
    ];

    // Relationships
    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function schemeUnit(): BelongsTo
    {
        return $this->belongsTo(SchemeUnit::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function apl01Form(): BelongsTo
    {
        return $this->belongsTo(Apl01Form::class, 'apl01_form_id');
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(Apl02Evidence::class);
    }

    public function assessorReviews(): HasMany
    {
        return $this->hasMany(Apl02AssessorReview::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByAssessee($query, $assesseeId)
    {
        return $query->where('assessee_id', $assesseeId);
    }

    public function scopeByAssessor($query, $assessorId)
    {
        return $query->where('assessor_id', $assessorId);
    }

    public function scopeByScheme($query, $schemeId)
    {
        return $query->where('scheme_id', $schemeId);
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeCompetent($query)
    {
        return $query->where('assessment_result', 'competent');
    }

    public function scopeNotYetCompetent($query)
    {
        return $query->where('assessment_result', 'not_yet_competent');
    }

    public function scopePending($query)
    {
        return $query->where('assessment_result', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'not_started' => 'Not Started',
            'in_progress' => 'In Progress',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'competent' => 'Competent',
            'not_yet_competent' => 'Not Yet Competent',
            'completed' => 'Completed',
            default => ucfirst($this->status),
        };
    }

    public function getAssessmentResultLabelAttribute(): string
    {
        return match($this->assessment_result) {
            'pending' => 'Pending',
            'competent' => 'Competent',
            'not_yet_competent' => 'Not Yet Competent',
            'requires_more_evidence' => 'Requires More Evidence',
            default => ucfirst($this->assessment_result),
        };
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsCompetentAttribute(): bool
    {
        return $this->assessment_result === 'competent';
    }

    public function getIsSubmittedAttribute(): bool
    {
        return in_array($this->status, ['submitted', 'under_review', 'completed']);
    }

    public function getCanEditAttribute(): bool
    {
        return !$this->is_locked && !in_array($this->status, ['under_review', 'completed']);
    }

    public function getAssessmentDurationAttribute(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }
        return null;
    }

    // Helper Methods
    public function calculateCompletionPercentage(): void
    {
        $totalElements = $this->schemeUnit->elements()->count();
        if ($totalElements === 0) {
            $this->completion_percentage = 0;
            return;
        }

        $mappedElements = Apl02EvidenceMap::whereHas('evidence', function($query) {
            $query->where('apl02_unit_id', $this->id);
        })->distinct('scheme_element_id')->count('scheme_element_id');

        $this->completion_percentage = ($mappedElements / $totalElements) * 100;
    }

    public function updateEvidenceCount(): void
    {
        $this->total_evidence = $this->evidence()->count();
        $this->save();
    }

    public function submit($userId): bool
    {
        if ($this->is_locked || $this->is_submitted) {
            return false;
        }

        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->submitted_by = $userId;

        // Auto-assign assessor from event if not already assigned
        $autoAssignedAssessor = null;
        if (!$this->assessor_id) {
            $assessorUserId = $this->getAssessorFromEventAssessors();
            if ($assessorUserId) {
                $this->assessor_id = $assessorUserId;
                $this->assigned_at = now();
                $this->status = 'under_review';
                $autoAssignedAssessor = $assessorUserId;
            }
        }

        $saved = $this->save();

        // Auto-create initial review record if assessor was auto-assigned
        if ($saved && $autoAssignedAssessor && !$this->assessorReviews()->where('review_type', 'initial_review')->exists()) {
            $this->assessorReviews()->create([
                'assessor_id' => $autoAssignedAssessor,
                'review_type' => 'initial_review',
                'decision' => 'pending',
                'status' => 'draft',
            ]);
        }

        return $saved;
    }

    /**
     * Get assessor user_id from event's confirmed assessors.
     * Prioritizes: lead assessor first, then any confirmed assessor with a user account.
     */
    public function getAssessorFromEventAssessors(): ?int
    {
        if (!$this->event_id || !$this->event) {
            return null;
        }

        // Get confirmed assessors from the event, prioritizing lead assessor
        $eventAssessor = $this->event->assessors()
            ->where('status', 'confirmed')
            ->whereHas('assessor', function ($query) {
                $query->whereNotNull('user_id');
            })
            ->with('assessor')
            ->orderByRaw("CASE WHEN role = 'lead' THEN 0 ELSE 1 END")
            ->first();

        return $eventAssessor?->assessor?->user_id;
    }

    public function assignToAssessor($assessorId): bool
    {
        $this->assessor_id = $assessorId;
        $this->assigned_at = now();
        $this->status = 'under_review';
        $saved = $this->save();

        // Auto-create initial review record if not exists
        if ($saved && !$this->assessorReviews()->where('review_type', 'initial_review')->exists()) {
            $this->assessorReviews()->create([
                'assessor_id' => $assessorId,
                'review_type' => 'initial_review',
                'decision' => 'pending',
                'status' => 'draft',
            ]);
        }

        return $saved;
    }

    public function startAssessment(): bool
    {
        if (!$this->assessor_id) {
            return false;
        }

        $this->started_at = now();
        if ($this->status === 'submitted') {
            $this->status = 'under_review';
        }
        return $this->save();
    }

    public function completeAssessment($result, $score = null, $notes = null): bool
    {
        $this->assessment_result = $result;
        $this->score = $score;
        $this->assessment_notes = $notes;
        $this->completed_at = now();
        $this->status = $result === 'competent' ? 'competent' : 'not_yet_competent';
        return $this->save();
    }

    public function lock(): bool
    {
        $this->is_locked = true;
        $this->locked_at = now();
        return $this->save();
    }

    public function unlock(): bool
    {
        $this->is_locked = false;
        $this->locked_at = null;
        return $this->save();
    }
}
