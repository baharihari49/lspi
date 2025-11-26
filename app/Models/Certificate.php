<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assessment_result_id',
        'assessee_id',
        'scheme_id',
        'issued_by',
        'certificate_number',
        'registration_number',
        'qr_code',
        'verification_url',
        'status',
        'holder_name',
        'holder_id_number',
        'competency_achieved',
        'unit_codes',
        'scheme_name',
        'scheme_code',
        'scheme_level',
        'issuing_organization',
        'issuing_organization_license',
        'issuing_organization_address',
        'assessment_date',
        'issue_date',
        'valid_from',
        'valid_until',
        'validity_period_months',
        'signatories',
        'template_name',
        'language',
        'custom_fields',
        'pdf_path',
        'pdf_url',
        'pdf_file_size',
        'pdf_generated_at',
        'qr_code_path',
        'qr_scan_count',
        'is_verified',
        'is_public',
        'verification_count',
        'renewed_from_certificate_id',
        'renewed_by_certificate_id',
        'is_renewable',
        'auto_generated',
        'renewal_notification_sent_at',
        'revoked_at',
        'revoked_by',
        'revocation_reason',
        'notes',
        'special_conditions',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'unit_codes' => 'array',
        'signatories' => 'array',
        'custom_fields' => 'array',
        'metadata' => 'array',
        'assessment_date' => 'date',
        'issue_date' => 'date',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'pdf_generated_at' => 'datetime',
        'renewal_notification_sent_at' => 'date',
        'revoked_at' => 'date',
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
        'is_renewable' => 'boolean',
        'auto_generated' => 'boolean',
    ];

    // Relationships
    public function assessmentResult(): BelongsTo
    {
        return $this->belongsTo(AssessmentResult::class);
    }

    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function renewedFrom(): BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'renewed_from_certificate_id');
    }

    public function renewedBy(): BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'renewed_by_certificate_id');
    }

    public function qrValidations(): HasMany
    {
        return $this->hasMany(CertificateQrValidation::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CertificateLog::class);
    }

    public function revocations(): HasMany
    {
        return $this->hasMany(CertificateRevoke::class);
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(CertificateRenewal::class, 'original_certificate_id');
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->valid_until < now();
    }

    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    public function isValid(): bool
    {
        return $this->isActive() && !$this->isExpired() && !$this->isRevoked();
    }

    public function daysUntilExpiry(): int
    {
        return now()->diffInDays($this->valid_until, false);
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        $daysUntil = $this->daysUntilExpiry();
        return $daysUntil > 0 && $daysUntil <= $days;
    }
}
