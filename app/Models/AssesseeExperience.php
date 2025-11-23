<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssesseeExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assessee_experience';

    protected $fillable = [
        'assessee_id',
        'experience_type',
        'title',
        'organization',
        'location',
        'description',
        'start_date',
        'end_date',
        'is_ongoing',
        'responsibilities',
        'outcomes',
        'skills_gained',
        'certificate_number',
        'issuing_organization',
        'issue_date',
        'expiry_date',
        'publication_title',
        'publisher',
        'publication_date',
        'publication_url',
        'doi',
        'award_name',
        'award_issuer',
        'award_date',
        'evidence_file',
        'reference_url',
        'relevance_to_certification',
        'relevance_score',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'publication_date' => 'date',
        'award_date' => 'date',
        'is_ongoing' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeOngoing($query)
    {
        return $query->where('is_ongoing', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('experience_type', $type);
    }

    // Accessors
    public function getExperienceTypeLabelAttribute()
    {
        $labels = [
            'professional' => 'Professional Experience',
            'project' => 'Project',
            'volunteer' => 'Volunteer Work',
            'certification' => 'Certification',
            'training' => 'Training',
            'publication' => 'Publication',
            'award' => 'Award',
            'other' => 'Other',
        ];

        return $labels[$this->experience_type] ?? $this->experience_type;
    }

    public function getDurationAttribute()
    {
        if (!$this->start_date) {
            return null;
        }

        $end = $this->is_ongoing ? now() : $this->end_date;

        if (!$end) {
            return null;
        }

        $diff = $this->start_date->diff($end);
        $years = $diff->y;
        $months = $diff->m;

        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ' ' . ($years == 1 ? 'year' : 'years');
        }
        if ($months > 0) {
            $parts[] = $months . ' ' . ($months == 1 ? 'month' : 'months');
        }

        return implode(' ', $parts) ?: 'Less than a month';
    }
}
