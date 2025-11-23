<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Apl02Evidence extends Model
{
    use SoftDeletes;

    protected $table = 'apl02_evidence';

    protected $fillable = [
        'assessee_id',
        'apl02_unit_id',
        'evidence_number',
        'title',
        'description',
        'evidence_type',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'original_filename',
        'external_url',
        'evidence_date',
        'validity_start_date',
        'validity_end_date',
        'issued_by',
        'issuer_organization',
        'certificate_number',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'assessment_result',
        'relevance_score',
        'assessor_notes',
        'is_authentic',
        'is_current',
        'is_sufficient',
        'is_public',
        'is_primary',
        'submitted_at',
        'submitted_by',
        'metadata',
        'notes',
        'view_count',
    ];

    protected $casts = [
        'evidence_date' => 'date',
        'validity_start_date' => 'date',
        'validity_end_date' => 'date',
        'verified_at' => 'datetime',
        'submitted_at' => 'datetime',
        'metadata' => 'array',
        'relevance_score' => 'decimal:2',
        'file_size' => 'integer',
        'view_count' => 'integer',
        'is_authentic' => 'boolean',
        'is_current' => 'boolean',
        'is_sufficient' => 'boolean',
        'is_public' => 'boolean',
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function apl02Unit(): BelongsTo
    {
        return $this->belongsTo(Apl02Unit::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function evidenceMaps(): HasMany
    {
        return $this->hasMany(Apl02EvidenceMap::class);
    }

    // Scopes
    public function scopeByAssessee($query, $assesseeId)
    {
        return $query->where('assessee_id', $assesseeId);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('apl02_unit_id', $unitId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('evidence_type', $type);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    public function scopeValid($query)
    {
        return $query->where('assessment_result', 'valid');
    }

    public function scopeInvalid($query)
    {
        return $query->where('assessment_result', 'invalid');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Accessors
    public function getEvidenceTypeLabelAttribute(): string
    {
        return match($this->evidence_type) {
            'document' => 'Document',
            'certificate' => 'Certificate',
            'work_sample' => 'Work Sample',
            'project' => 'Project',
            'photo' => 'Photo',
            'video' => 'Video',
            'presentation' => 'Presentation',
            'log_book' => 'Log Book',
            'portfolio' => 'Portfolio',
            'other' => 'Other',
            default => ucfirst($this->evidence_type),
        };
    }

    public function getVerificationStatusLabelAttribute(): string
    {
        return match($this->verification_status) {
            'pending' => 'Pending',
            'verified' => 'Verified',
            'rejected' => 'Rejected',
            'requires_clarification' => 'Requires Clarification',
            default => ucfirst($this->verification_status),
        };
    }

    public function getAssessmentResultLabelAttribute(): string
    {
        return match($this->assessment_result) {
            'pending' => 'Pending',
            'valid' => 'Valid',
            'invalid' => 'Invalid',
            'insufficient' => 'Insufficient',
            default => ucfirst($this->assessment_result),
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return Storage::url($this->file_path);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->validity_end_date) {
            return false;
        }

        return $this->validity_end_date->isPast();
    }

    public function getIsVerifiedAttribute(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function getIsValidAttribute(): bool
    {
        return $this->assessment_result === 'valid';
    }

    // Helper Methods
    public function generateEvidenceNumber(): string
    {
        $year = date('Y');
        $lastEvidence = static::where('evidence_number', 'like', "EVD-{$year}-%")
            ->orderBy('evidence_number', 'desc')
            ->first();

        $newNumber = $lastEvidence
            ? ((int)substr($lastEvidence->evidence_number, -4) + 1)
            : 1;

        return 'EVD-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function verify($verifierId, $notes = null): bool
    {
        $this->verification_status = 'verified';
        $this->verified_by = $verifierId;
        $this->verified_at = now();
        $this->verification_notes = $notes;
        return $this->save();
    }

    public function reject($verifierId, $notes): bool
    {
        $this->verification_status = 'rejected';
        $this->verified_by = $verifierId;
        $this->verified_at = now();
        $this->verification_notes = $notes;
        return $this->save();
    }

    public function assess($result, $score = null, $notes = null): bool
    {
        $this->assessment_result = $result;
        $this->relevance_score = $score;
        $this->assessor_notes = $notes;
        return $this->save();
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function deleteFile(): bool
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            return Storage::delete($this->file_path);
        }
        return false;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($evidence) {
            if (!$evidence->evidence_number) {
                $evidence->evidence_number = $evidence->generateEvidenceNumber();
            }
        });

        static::deleting(function ($evidence) {
            if ($evidence->isForceDeleting()) {
                $evidence->deleteFile();
            }
        });
    }
}
