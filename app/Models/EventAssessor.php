<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAssessor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id', 'assessor_id', 'event_session_id',
        'role', 'notes', 'status',
        'invited_at', 'confirmed_at', 'rejected_at', 'rejection_reason',
        'honorarium_amount', 'payment_status', 'paid_at',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
        'honorarium_amount' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Assessor::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }
}
