<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateLog extends Model
{
    protected $fillable = [
        'certificate_id',
        'user_id',
        'action',
        'description',
        'changes',
        'ip_address',
        'user_agent',
        'metadata',
        'log_level',
        'log_category',
    ];

    protected $casts = [
        'changes' => 'array',
        'metadata' => 'array',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
