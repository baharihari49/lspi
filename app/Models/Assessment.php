<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_number',
        'title',
        'description',
        'assessee_id',
        'scheme_id',
        'event_id',
        'lead_assessor_id',
        'assessment_method',
        'assessment_type',
        'scheduled_date',
        'scheduled_time',
        'venue',
        'tuk_type',
        'tuk_id',
        'status',
        'started_at',
        'completed_at',
        'duration_minutes',
        'planned_duration_minutes',
        'overall_result',
        'overall_score',
        'notes',
        'preparation_notes',
        'special_requirements',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'overall_score' => 'decimal:2',
        'metadata' => 'array',
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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function leadAssessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_assessor_id');
    }

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(Tuk::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function assessmentUnits(): HasMany
    {
        return $this->hasMany(AssessmentUnit::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssessmentDocument::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(AssessmentVerification::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(AssessmentFeedback::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(AssessmentResult::class);
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

    public function scopeByScheme($query, $schemeId)
    {
        return $query->where('scheme_id', $schemeId);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'under_review', 'verified', 'approved']);
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return in_array($this->status, ['completed', 'under_review', 'verified', 'approved']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function canBeModified(): bool
    {
        return in_array($this->status, ['draft', 'scheduled']);
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'scheduled' => 'blue',
            'in_progress' => 'yellow',
            'completed' => 'purple',
            'under_review' => 'orange',
            'verified' => 'indigo',
            'approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
