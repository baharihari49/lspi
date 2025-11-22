<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessorExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assessor_experience';

    protected $fillable = [
        'assessor_id', 'organization_name', 'position', 'experience_type',
        'description', 'start_date', 'end_date', 'is_current', 'location',
        'reference_name', 'reference_contact', 'document_file_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Assessor::class);
    }

    public function documentFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'document_file_id');
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('experience_type', $type);
    }

    // Accessor for duration
    public function getDurationAttribute()
    {
        $start = $this->start_date;
        $end = $this->is_current ? now() : $this->end_date;

        if (!$start || !$end) {
            return null;
        }

        return $start->diffInMonths($end);
    }
}
