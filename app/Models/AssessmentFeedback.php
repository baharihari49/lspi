<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentFeedback extends Model
{
    use SoftDeletes;

    protected $table = 'assessment_feedback';

    protected $fillable = [
        'assessment_id',
        'assessment_unit_id',
        'provider_id',
        'recipient_id',
        'feedback_number',
        'title',
        'feedback_type',
        'feedback_method',
        'positive_aspects',
        'areas_for_improvement',
        'specific_examples',
        'recommendations',
        'strengths',
        'weaknesses',
        'development_areas',
        'action_items',
        'provided_at',
        'delivered_at',
        'is_confidential',
        'recipient_acknowledgment',
        'acknowledged_at',
        'recipient_comments',
        'requires_follow_up',
        'follow_up_date',
        'follow_up_notes',
        'followed_up_at',
        'metadata',
        'display_order',
    ];

    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'development_areas' => 'array',
        'action_items' => 'array',
        'provided_at' => 'datetime',
        'delivered_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'follow_up_date' => 'date',
        'followed_up_at' => 'datetime',
        'is_confidential' => 'boolean',
        'requires_follow_up' => 'boolean',
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

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Scopes
    public function scopeFormative($query)
    {
        return $query->where('feedback_type', 'formative');
    }

    public function scopeSummative($query)
    {
        return $query->where('feedback_type', 'summative');
    }

    public function scopeAcknowledged($query)
    {
        return $query->whereNotNull('acknowledged_at');
    }

    public function scopeRequiresFollowUp($query)
    {
        return $query->where('requires_follow_up', true);
    }

    public function scopeConfidential($query)
    {
        return $query->where('is_confidential', true);
    }

    // Helper methods
    public function isAcknowledged(): bool
    {
        return !is_null($this->acknowledged_at);
    }

    public function isDelivered(): bool
    {
        return !is_null($this->delivered_at);
    }

    public function requiresFollowUp(): bool
    {
        return $this->requires_follow_up;
    }

    public function isConfidential(): bool
    {
        return $this->is_confidential;
    }
}
