<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateQrValidation extends Model
{
    protected $table = 'certificate_qr_validation';

    protected $fillable = [
        'certificate_id',
        'scan_id',
        'scanned_at',
        'scan_method',
        'validation_status',
        'scanner_ip',
        'scanner_user_agent',
        'scanner_device_type',
        'scanner_location',
        'referrer_url',
        'source',
        'validation_response',
        'response_time_ms',
        'is_suspicious',
        'suspicious_reason',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'scanner_location' => 'array',
        'validation_response' => 'array',
        'is_suspicious' => 'boolean',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}
