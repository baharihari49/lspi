<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apl01Answer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'apl01_form_id',
        'form_field_id',
        'answer_value',
        'answer_json',
        'answer_file',
        'answer_files',
        'is_valid',
        'validation_errors',
        'review_status',
        'review_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'answer_json' => 'array',
        'answer_files' => 'array',
        'is_valid' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function form(): BelongsTo
    {
        return $this->belongsTo(Apl01Form::class, 'apl01_form_id');
    }

    public function formField(): BelongsTo
    {
        return $this->belongsTo(Apl01FormField::class, 'form_field_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('review_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('review_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('review_status', 'rejected');
    }

    public function scopeNeedsClarification($query)
    {
        return $query->where('review_status', 'needs_clarification');
    }

    // Accessors
    public function getHasFileAttribute()
    {
        return !empty($this->answer_file) || !empty($this->answer_files);
    }

    public function getAnswerDisplayAttribute()
    {
        if ($this->formField->field_type === 'yesno') {
            return $this->answer_value === '1' || $this->answer_value === 'yes' ? 'Yes' : 'No';
        }

        if ($this->formField->field_type === 'rating') {
            return $this->answer_value . ' / 5';
        }

        if ($this->formField->is_multiple_choice && $this->answer_json) {
            return implode(', ', $this->answer_json);
        }

        if ($this->formField->is_file_upload) {
            if ($this->answer_files) {
                return count($this->answer_files) . ' file(s)';
            }
            return $this->answer_file ? 'File uploaded' : 'No file';
        }

        return $this->answer_value ?? '-';
    }

    public function getReviewStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'needs_clarification' => 'Needs Clarification',
        ];

        return $labels[$this->review_status] ?? ucfirst($this->review_status);
    }

    public function getReviewStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'needs_clarification' => 'orange',
        ];

        return $colors[$this->review_status] ?? 'gray';
    }
}
