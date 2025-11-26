<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultApproval extends Model
{
    use SoftDeletes;

    protected $table = 'result_approval';

    protected $fillable = [
        'assessment_result_id',
        'approver_id',
        'approval_level',
        'sequence_order',
        'approver_role',
        'status',
        'decision',
        'comments',
        'recommendations',
        'checklist',
        'issues_identified',
        'required_changes',
        'conditions',
        'assigned_at',
        'reviewed_at',
        'decision_at',
        'review_duration_minutes',
        'due_date',
        'is_overdue',
        'revision_number',
        'previous_approval_id',
        'delegated_to',
        'delegated_at',
        'delegation_notes',
        'approver_notified',
        'approver_notified_at',
        'metadata',
    ];

    protected $casts = [
        'checklist' => 'array',
        'issues_identified' => 'array',
        'required_changes' => 'array',
        'assigned_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'decision_at' => 'datetime',
        'due_date' => 'date',
        'is_overdue' => 'boolean',
        'delegated_at' => 'datetime',
        'approver_notified' => 'boolean',
        'approver_notified_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function assessmentResult(): BelongsTo
    {
        return $this->belongsTo(AssessmentResult::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function previousApproval(): BelongsTo
    {
        return $this->belongsTo(ResultApproval::class, 'previous_approval_id');
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegated_to');
    }

    public function delegatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegated_to');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInReview($query)
    {
        return $query->where('status', 'in_review');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('approval_level', $level);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('approver_role', $role);
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_overdue', true);
    }

    public function scopeDelegated($query)
    {
        return $query->whereNotNull('delegated_to');
    }

    // Helper methods
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isOverdue(): bool
    {
        return $this->is_overdue;
    }

    public function isDelegated(): bool
    {
        return !is_null($this->delegated_to);
    }

    public function hasBeenReviewed(): bool
    {
        return !is_null($this->reviewed_at);
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_review' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'returned_for_revision' => 'orange',
            'withdrawn' => 'gray',
            default => 'gray',
        };
    }
}
