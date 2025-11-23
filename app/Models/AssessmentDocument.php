<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_id',
        'assessment_unit_id',
        'assessment_criteria_id',
        'uploaded_by',
        'document_number',
        'title',
        'description',
        'document_type',
        'evidence_type',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'original_filename',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'relevance',
        'assessor_comments',
        'metadata',
        'display_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'verified_at' => 'datetime',
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

    public function assessmentCriteria(): BelongsTo
    {
        return $this->belongsTo(AssessmentCriteria::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeByDocumentType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeHighlyRelevant($query)
    {
        return $query->where('relevance', 'highly_relevant');
    }

    // Helper methods
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
}
