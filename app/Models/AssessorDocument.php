<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessorDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessor_id', 'document_type_id', 'title', 'description', 'document_number',
        'file_id', 'issued_date', 'expiry_date', 'verification_status',
        'verification_notes', 'verified_by', 'verified_at', 'status_id', 'uploaded_by',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Assessor::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(MasterDocumentType::class, 'document_type_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
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
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }
}
