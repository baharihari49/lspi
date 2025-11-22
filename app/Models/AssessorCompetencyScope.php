<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessorCompetencyScope extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assessor_competency_scope';

    protected $fillable = [
        'assessor_id', 'scheme_code', 'scheme_name', 'competency_unit_code',
        'competency_unit_title', 'certificate_number', 'certificate_issued_date',
        'certificate_expiry_date', 'approval_status', 'approval_notes',
        'approved_by', 'approved_at', 'is_active',
    ];

    protected $casts = [
        'certificate_issued_date' => 'date',
        'certificate_expiry_date' => 'date',
        'approved_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Assessor::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('certificate_expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->where('certificate_expiry_date', '<', now())
                    ->orWhere('approval_status', 'expired');
    }
}
