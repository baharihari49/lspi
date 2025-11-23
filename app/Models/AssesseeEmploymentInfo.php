<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssesseeEmploymentInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assessee_employment_info';

    protected $fillable = [
        'assessee_id',
        'company_name',
        'company_industry',
        'company_location',
        'company_website',
        'position_title',
        'department',
        'employment_type',
        'job_description',
        'start_date',
        'end_date',
        'is_current',
        'responsibilities',
        'achievements',
        'skills_used',
        'supervisor_name',
        'supervisor_title',
        'supervisor_contact',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
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

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    // Accessors
    public function getDurationAttribute()
    {
        if (!$this->start_date) {
            return null;
        }

        $end = $this->is_current ? now() : $this->end_date;

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
