<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apl02Form extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'apl01_form_id',
        'assessee_id',
        'scheme_id',
        'event_id',
        'form_number',
        'status',
        'self_assessment',
        'portfolio_summary',
        'evidence_files',
        'work_experience',
        'training_education',
        'declaration_agreed',
        'declaration_signed_at',
        'declaration_signature',
        'submitted_at',
        'submitted_by',
        'assessor_id',
        'assigned_at',
        'review_started_at',
        'review_completed_at',
        'assessment_result',
        'assessor_notes',
        'assessor_feedback',
        'recommendations',
        'revision_notes',
        'completion_percentage',
        'completed_at',
        'completed_by',
        'auto_generated',
        'admin_notes',
    ];

    protected $casts = [
        'self_assessment' => 'array',
        'evidence_files' => 'array',
        'work_experience' => 'array',
        'training_education' => 'array',
        'revision_notes' => 'array',
        'declaration_agreed' => 'boolean',
        'declaration_signed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'assigned_at' => 'datetime',
        'review_started_at' => 'datetime',
        'review_completed_at' => 'datetime',
        'completed_at' => 'datetime',
        'auto_generated' => 'boolean',
        'completion_percentage' => 'integer',
    ];

    // Relationships
    public function apl01Form(): BelongsTo
    {
        return $this->belongsTo(Apl01Form::class);
    }

    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeByAssessee($query, $assesseeId)
    {
        return $query->where('assessee_id', $assesseeId);
    }

    public function scopeByAssessor($query, $assessorId)
    {
        return $query->where('assessor_id', $assessorId);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'revision_required' => 'Revisi Diperlukan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'draft' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'revision_required' => 'orange',
            'approved' => 'green',
            'rejected' => 'red',
            'completed' => 'green',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getAssessmentResultLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu',
            'competent' => 'Kompeten',
            'not_yet_competent' => 'Belum Kompeten',
        ];

        return $labels[$this->assessment_result] ?? ucfirst($this->assessment_result);
    }

    public function getIsEditableAttribute(): bool
    {
        return in_array($this->status, ['draft', 'revision_required']);
    }

    public function getIsSubmittableAttribute(): bool
    {
        // Submittable if draft, has self assessment filled, and declaration agreed
        return $this->status === 'draft'
            && $this->declaration_agreed
            && !empty($this->self_assessment)
            && $this->hasAllSelfAssessmentFilled();
    }

    /**
     * Check if all self assessment units have been filled
     */
    public function hasAllSelfAssessmentFilled(): bool
    {
        if (empty($this->self_assessment)) {
            return false;
        }

        foreach ($this->self_assessment as $assessment) {
            if (!isset($assessment['is_competent']) || $assessment['is_competent'] === null) {
                return false;
            }
        }

        return true;
    }

    public function getIsReviewableAttribute(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    // Helper Methods
    public function generateFormNumber(): string
    {
        $year = date('Y');
        $lastForm = static::where('form_number', 'like', "APL02-{$year}-%")
            ->orderBy('form_number', 'desc')
            ->first();

        if ($lastForm) {
            $lastNumber = (int) substr($lastForm->form_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'APL02-' . $year . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function calculateCompletion(): int
    {
        $totalPoints = 0;
        $earnedPoints = 0;

        // Self assessment - weighted heavily (50 points)
        $totalPoints += 50;
        if (!empty($this->self_assessment)) {
            $filledCount = 0;
            $totalUnits = count($this->self_assessment);
            foreach ($this->self_assessment as $assessment) {
                if (isset($assessment['is_competent']) && $assessment['is_competent'] !== null) {
                    $filledCount++;
                }
            }
            if ($totalUnits > 0) {
                $earnedPoints += (int) round(($filledCount / $totalUnits) * 50);
            }
        }

        // Evidence files - optional but adds to completion (20 points)
        $totalPoints += 20;
        if (!empty($this->evidence_files) && count($this->evidence_files) > 0) {
            $earnedPoints += 20;
        }

        // Portfolio summary - optional (10 points)
        $totalPoints += 10;
        if (!empty($this->portfolio_summary)) {
            $earnedPoints += 10;
        }

        // Declaration - required (20 points)
        $totalPoints += 20;
        if ($this->declaration_agreed) {
            $earnedPoints += 20;
        }

        return $totalPoints > 0 ? (int) round(($earnedPoints / $totalPoints) * 100) : 0;
    }

    public function updateCompletion(): self
    {
        $this->completion_percentage = $this->calculateCompletion();
        $this->save();

        return $this;
    }

    public function submit(?int $userId = null): self
    {
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->submitted_by = $userId ?? auth()->id();
        $this->save();

        return $this;
    }

    public function assignAssessor(int $assessorId): self
    {
        $this->assessor_id = $assessorId;
        $this->assigned_at = now();
        $this->status = 'under_review';
        $this->save();

        return $this;
    }

    public function startReview(): self
    {
        $this->review_started_at = now();
        $this->save();

        return $this;
    }

    public function approve(?string $notes = null, ?string $feedback = null): self
    {
        $this->status = 'approved';
        $this->assessment_result = 'competent';
        $this->assessor_notes = $notes;
        $this->assessor_feedback = $feedback;
        $this->review_completed_at = now();
        $this->save();

        // Update APL-01 flow status
        if ($this->apl01Form) {
            $this->apl01Form->update([
                'flow_status' => 'apl02_completed',
            ]);
        }

        return $this;
    }

    public function reject(string $notes, ?string $feedback = null): self
    {
        $this->status = 'rejected';
        $this->assessment_result = 'not_yet_competent';
        $this->assessor_notes = $notes;
        $this->assessor_feedback = $feedback;
        $this->review_completed_at = now();
        $this->save();

        return $this;
    }

    public function requestRevision(array $revisionNotes): self
    {
        $this->status = 'revision_required';
        $this->revision_notes = $revisionNotes;
        $this->save();

        return $this;
    }

    public function resubmit(): self
    {
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->revision_notes = null;
        $this->save();

        return $this;
    }

    /**
     * Initialize self-assessment from scheme units
     */
    public function initializeSelfAssessment(): self
    {
        if (!$this->scheme) {
            return $this;
        }

        $units = $this->scheme->units;
        $selfAssessment = [];

        foreach ($units as $unit) {
            $selfAssessment[] = [
                'unit_id' => $unit->id,
                'unit_code' => $unit->code,
                'unit_title' => $unit->title,
                'is_competent' => null, // null = not answered, true = competent, false = not yet competent
                'evidence_description' => '',
                'notes' => '',
            ];
        }

        $this->self_assessment = $selfAssessment;
        $this->save();

        return $this;
    }
}
