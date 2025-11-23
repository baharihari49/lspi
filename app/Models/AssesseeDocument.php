<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssesseeDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessee_id',
        'document_type_id',
        'document_name',
        'document_number',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'is_required',
        'is_public',
        'order',
        'uploaded_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_required' => 'boolean',
        'is_public' => 'boolean',
    ];

    // Relationships
    public function assessee(): BelongsTo
    {
        return $this->belongsTo(Assessee::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(MasterDocumentType::class, 'document_type_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
