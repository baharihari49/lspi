<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scheme_version_id',
        'document_type_id',
        'title',
        'description',
        'file_id',
        'issued_date',
        'expiry_date',
        'status_id',
        'uploaded_by',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function schemeVersion(): BelongsTo
    {
        return $this->belongsTo(SchemeVersion::class);
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

    // Scopes
    public function scopeByType($query, $typeId)
    {
        return $query->where('document_type_id', $typeId);
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '<', now());
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', now());
        });
    }
}
