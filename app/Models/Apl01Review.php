<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apl01Review extends Model
{
    use SoftDeletes;

    protected $table = 'apl01_review';

    protected $fillable = [
        'apl01_form_id',
        'review_level',
        'review_level_name',
        'reviewer_id',
        'reviewer_role',
        'decision',
        'review_notes',
        'checklist_items',
        'score',
        'field_feedback',
        'attachments',
        'assigned_at',
        'started_at',
        'completed_at',
        'deadline',
        'review_duration',
        'is_escalated',
        'escalation_reason',
        'escalated_at',
        'escalated_to',
        'notification_sent',
        'notification_sent_at',
        'is_current',
        'is_final',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'checklist_items' => 'array',
        'field_feedback' => 'array',
        'attachments' => 'array',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'deadline' => 'datetime',
        'escalated_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'is_escalated' => 'boolean',
        'notification_sent' => 'boolean',
        'is_current' => 'boolean',
        'is_final' => 'boolean',
        'review_level' => 'integer',
        'score' => 'integer',
        'review_duration' => 'integer',
    ];

    // Relationships
    public function form(): BelongsTo
    {
        return $this->belongsTo(Apl01Form::class, 'apl01_form_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('decision', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('decision', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('decision', 'rejected');
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    public function scopeEscalated($query)
    {
        return $query->where('is_escalated', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->where('decision', 'pending');
    }

    public function scopeByReviewer($query, $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    // Accessors
    public function getDecisionLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'approved_with_notes' => 'Approved with Notes',
            'returned' => 'Returned',
            'forwarded' => 'Forwarded',
            'on_hold' => 'On Hold',
        ];

        return $labels[$this->decision] ?? ucfirst($this->decision);
    }

    public function getDecisionColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'approved_with_notes' => 'blue',
            'returned' => 'orange',
            'forwarded' => 'purple',
            'on_hold' => 'gray',
        ];

        return $colors[$this->decision] ?? 'gray';
    }

    public function getIsOverdueAttribute()
    {
        return $this->deadline && $this->deadline->isPast() && $this->decision === 'pending';
    }

    public function getIsCompletedAttribute()
    {
        return in_array($this->decision, ['approved', 'rejected', 'approved_with_notes', 'forwarded']);
    }

    public function getDurationInMinutesAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }

        return null;
    }

    // Helper Methods
    public function startReview($userId = null)
    {
        $this->started_at = now();
        $this->save();

        return $this;
    }

    public function approve($notes = null, $userId = null)
    {
        $this->decision = 'approved';
        $this->review_notes = $notes;
        $this->completed_at = now();
        $this->review_duration = $this->duration_in_minutes;
        $this->save();

        // Move form to next review level or complete
        if ($this->is_final) {
            $this->form->status = 'approved';
            $this->form->completed_at = now();
            $this->form->completed_by = $userId ?? auth()->id();
            $this->form->save();
        } else {
            // Create next review level
            $this->form->reviews()->create([
                'review_level' => $this->review_level + 1,
                'review_level_name' => 'Review Level ' . ($this->review_level + 1),
                'reviewer_id' => 1, // Should be assigned properly
                'decision' => 'pending',
                'assigned_at' => now(),
                'is_current' => true,
                'created_by' => $userId ?? auth()->id(),
            ]);

            $this->is_current = false;
            $this->save();

            $this->form->current_review_level = $this->review_level + 1;
            $this->form->save();
        }

        return $this;
    }

    public function reject($notes = null, $userId = null)
    {
        $this->decision = 'rejected';
        $this->review_notes = $notes;
        $this->completed_at = now();
        $this->review_duration = $this->duration_in_minutes;
        $this->save();

        // Update form status
        $this->form->status = 'rejected';
        $this->form->rejection_reason = $notes;
        $this->form->save();

        return $this;
    }

    public function escalate($reason, $escalateTo, $userId = null)
    {
        $this->is_escalated = true;
        $this->escalation_reason = $reason;
        $this->escalated_at = now();
        $this->escalated_to = $escalateTo;
        $this->save();

        return $this;
    }
}
