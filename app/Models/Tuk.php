<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tuk';

    protected $fillable = [
        'code', 'name', 'type', 'description', 'address', 'city', 'province', 'postal_code',
        'latitude', 'longitude', 'phone', 'email', 'pic_name', 'pic_phone',
        'capacity', 'room_count', 'area_size', 'status_id', 'is_active',
        'manager_id', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'area_size' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(TukFacility::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TukDocument::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(TukSchedule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
