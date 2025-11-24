<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateRenewal extends Model
{
    use SoftDeletes;

    protected $table = 'certificate_renewal';

    protected $fillable = [
        'original_certificate_id',
        'new_certificate_id',
        'assessee_id',
        'scheme_id',
        'requested_by',
        'processed_by',
        'renewal_number',
        'renewal_request_date',
        'renewal_due_date',
        'renewal_processed_date',
        'renewal_type',
        'status',
        'eligibility_checked',
        'is_eligible',
        'eligibility_criteria',
        'ineligibility_reason',
        'requires_reassessment',
        'renewal_assessment_id',
        'required_documents',
        'submitted_documents',
        'competency_verified',
        'competency_updates',
        'competency_notes',
        'cpd_required',
        'cpd_hours_required',
        'cpd_hours_completed',
        'cpd_activities',
        'renewal_fee',
        'fee_paid',
        'fee_paid_date',
        'payment_reference',
        'decision_notes',
        'rejection_reason',
        'conditions',
        'original_expiry_date',
        'new_expiry_date',
        'extension_months',
        'grace_period_used',
        'grace_period_days',
        'renewal_reminder_sent',
        'renewal_reminder_sent_at',
        'renewal_completed_notification_sent',
        'internal_notes',
        'notes_for_assessee',
        'metadata',
    ];

    protected $casts = [
        'renewal_request_date' => 'date',
        'renewal_due_date' => 'date',
        'renewal_processed_date' => 'date',
        'fee_paid_date' => 'date',
        'original_expiry_date' => 'date',
        'new_expiry_date' => 'date',
        'renewal_reminder_sent_at' => 'date',
        'eligibility_checked' => 'boolean',
        'is_eligible' => 'boolean',
        'requires_reassessment' => 'boolean',
        'competency_verified' => 'boolean',
        'cpd_required' => 'boolean',
        'fee_paid' => 'boolean',
        'grace_period_used' => 'boolean',
        'renewal_reminder_sent' => 'boolean',
        'renewal_completed_notification_sent' => 'boolean',
        'eligibility_criteria' => 'array',
        'required_documents' => 'array',
        'submitted_documents' => 'array',
        'competency_updates' => 'array',
        'cpd_activities' => 'array',
        'conditions' => 'array',
        'metadata' => 'array',
    ];

    public function originalCertificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'original_certificate_id');
    }

    public function newCertificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'new_certificate_id');
    }

    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function renewalAssessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'renewal_assessment_id');
    }
}
