<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_attendance';

    protected $fillable = [
        'event_id', 'event_session_id', 'user_id',
        'check_in_at', 'check_out_at', 'check_in_method', 'check_out_method',
        'check_in_location', 'check_out_location',
        'check_in_latitude', 'check_in_longitude',
        'status', 'notes', 'excuse_reason',
        'checked_in_by', 'checked_out_by',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'check_in_latitude' => 'decimal:7',
        'check_in_longitude' => 'decimal:7',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }
}
