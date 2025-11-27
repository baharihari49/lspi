<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'scheme_id', 'description', 'event_type',
        'start_date', 'end_date', 'registration_start', 'registration_end',
        'max_participants', 'current_participants', 'registration_fee',
        'status_id', 'is_published', 'is_active',
        'location', 'location_address',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'registration_fee' => 'decimal:2',
        'is_published' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class);
    }

    public function tuks(): HasMany
    {
        return $this->hasMany(EventTuk::class);
    }

    public function assessors(): HasMany
    {
        return $this->hasMany(EventAssessor::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(EventMaterial::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function apl01Forms(): HasMany
    {
        return $this->hasMany(Apl01Form::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}
