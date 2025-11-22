<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TukDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'tuk_id', 'document_type_id', 'title', 'description', 'file_id',
        'issued_date', 'expiry_date', 'status_id', 'uploaded_by',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(Tuk::class);
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

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }
}
