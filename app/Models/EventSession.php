<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id', 'session_code', 'name', 'description',
        'session_date', 'start_time', 'end_time',
        'max_participants', 'current_participants',
        'room', 'notes', 'status', 'is_active', 'order',
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function assessors(): HasMany
    {
        return $this->hasMany(EventAssessor::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(EventMaterial::class);
    }
}
