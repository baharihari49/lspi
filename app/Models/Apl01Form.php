<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apl01Form extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessee_id',
        'scheme_id',
        'event_id',
        'form_number',
        'submission_date',
        'full_name',
        'id_number',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'nationality',
        'email',
        'mobile',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'current_company',
        'current_position',
        'current_industry',
        'certification_purpose',
        'target_competency',
        'self_assessment',
        'supporting_documents',
        'declaration_agreed',
        'declaration_signed_at',
        'declaration_signature',
        'status',
        'submitted_at',
        'submitted_by',
        'completed_at',
        'completed_by',
        'current_review_level',
        'current_reviewer_id',
        'admin_notes',
        'rejection_reason',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'submission_date' => 'date',
        'self_assessment' => 'array',
        'supporting_documents' => 'array',
        'declaration_agreed' => 'boolean',
        'declaration_signed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_active' => 'boolean',
        'current_review_level' => 'integer',
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

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function currentReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_reviewer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Apl01Answer::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Apl01Review::class);
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

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByScheme($query, $schemeId)
    {
        return $query->where('scheme_id', $schemeId);
    }

    public function scopeByAssessee($query, $assesseeId)
    {
        return $query->where('assessee_id', $assesseeId);
    }

    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review', 'revised']);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'revised' => 'Revised',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'revised' => 'orange',
            'completed' => 'green',
            'cancelled' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function getIsSubmittableAttribute()
    {
        return $this->status === 'draft' && $this->declaration_agreed;
    }

    public function getCompletionPercentageAttribute()
    {
        $totalFields = $this->scheme->formFields()->where('is_required', true)->count();
        if ($totalFields === 0) {
            return 100;
        }

        $answeredFields = $this->answers()
            ->whereHas('formField', function ($query) {
                $query->where('is_required', true);
            })
            ->whereNotNull('answer_value')
            ->count();

        return round(($answeredFields / $totalFields) * 100);
    }

    public function getCurrentReviewAttribute()
    {
        return $this->reviews()
            ->where('is_current', true)
            ->first();
    }

    // Helper Methods
    public function autoFillFromAssessee()
    {
        if ($this->assessee) {
            $this->fill([
                'full_name' => $this->assessee->full_name,
                'id_number' => $this->assessee->id_number,
                'date_of_birth' => $this->assessee->date_of_birth,
                'place_of_birth' => $this->assessee->place_of_birth,
                'gender' => $this->assessee->gender,
                'nationality' => $this->assessee->nationality,
                'email' => $this->assessee->email,
                'mobile' => $this->assessee->mobile,
                'phone' => $this->assessee->phone,
                'address' => $this->assessee->address,
                'city' => $this->assessee->city,
                'province' => $this->assessee->province,
                'postal_code' => $this->assessee->postal_code,
                'current_company' => $this->assessee->current_company,
                'current_position' => $this->assessee->current_position,
                'current_industry' => $this->assessee->current_industry,
            ]);
        }

        return $this;
    }

    public function generateFormNumber()
    {
        $year = date('Y');
        $lastForm = static::where('form_number', 'like', "APL-01-{$year}-%")
            ->orderBy('form_number', 'desc')
            ->first();

        if ($lastForm) {
            $lastNumber = (int) substr($lastForm->form_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'APL-01-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function canBeSubmitted()
    {
        return $this->status === 'draft'
            && $this->declaration_agreed
            && $this->completion_percentage >= 100;
    }

    public function submit($userId = null)
    {
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->submitted_by = $userId ?? auth()->id();
        $this->save();

        // Create initial review record
        $this->reviews()->create([
            'review_level' => 1,
            'review_level_name' => 'Initial Review',
            'reviewer_id' => $this->scheme->default_reviewer_id ?? 1, // Need to add this to schemes table
            'decision' => 'pending',
            'assigned_at' => now(),
            'is_current' => true,
            'created_by' => $userId ?? auth()->id(),
        ]);

        $this->current_review_level = 1;
        $this->status = 'under_review';
        $this->save();

        return $this;
    }
}
