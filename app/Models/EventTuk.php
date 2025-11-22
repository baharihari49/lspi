<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventTuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_tuk';

    protected $fillable = [
        'event_id', 'tuk_id', 'event_session_id',
        'notes', 'is_primary', 'status',
        'confirmed_at', 'confirmed_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(Tuk::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
