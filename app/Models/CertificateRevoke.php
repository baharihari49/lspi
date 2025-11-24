<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateRevoke extends Model
{
    use SoftDeletes;

    protected $table = 'certificate_revoke';

    protected $fillable = [
        'certificate_id',
        'revoked_by',
        'approved_by',
        'revocation_number',
        'revocation_date',
        'revocation_request_date',
        'revocation_approval_date',
        'revocation_reason_category',
        'revocation_reason',
        'supporting_documents',
        'status',
        'is_appealable',
        'appeal_deadline',
        'appeal_submitted_at',
        'appeal_reason',
        'appeal_status',
        'affected_parties',
        'impact_notes',
        'holder_notified',
        'holder_notified_at',
        'public_notification_required',
        'public_notification_date',
        'is_reinstatable',
        'reinstatement_conditions',
        'reinstatement_eligible_from',
        'internal_notes',
        'metadata',
    ];

    protected $casts = [
        'revocation_date' => 'date',
        'revocation_request_date' => 'date',
        'revocation_approval_date' => 'date',
        'appeal_deadline' => 'date',
        'appeal_submitted_at' => 'date',
        'holder_notified_at' => 'date',
        'public_notification_date' => 'date',
        'reinstatement_eligible_from' => 'date',
        'supporting_documents' => 'array',
        'affected_parties' => 'array',
        'metadata' => 'array',
        'is_appealable' => 'boolean',
        'holder_notified' => 'boolean',
        'public_notification_required' => 'boolean',
        'is_reinstatable' => 'boolean',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
