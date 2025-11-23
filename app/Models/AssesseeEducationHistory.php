<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssesseeEducationHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assessee_education_history';

    protected $fillable = [
        'assessee_id',
        'institution_name',
        'institution_location',
        'institution_type',
        'education_level',
        'degree_name',
        'major',
        'minor',
        'start_date',
        'end_date',
        'is_current',
        'gpa',
        'gpa_scale',
        'honors',
        'achievements',
        'certificate_number',
        'graduation_date',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'graduation_date' => 'date',
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
    public function getEducationLevelLabelAttribute()
    {
        $labels = [
            'sd' => 'Elementary School',
            'smp' => 'Junior High School',
            'sma' => 'Senior High School',
            'diploma' => 'Diploma',
            's1' => 'Bachelor\'s Degree',
            's2' => 'Master\'s Degree',
            's3' => 'Doctorate',
            'other' => 'Other',
        ];

        return $labels[$this->education_level] ?? $this->education_level;
    }

    public function getDurationAttribute()
    {
        if (!$this->start_date) {
            return null;
        }

        $end = $this->is_current ? now() : ($this->end_date ?? $this->graduation_date);

        if (!$end) {
            return null;
        }

        $years = $this->start_date->diffInYears($end);

        return $years . ' ' . ($years == 1 ? 'year' : 'years');
    }
}
