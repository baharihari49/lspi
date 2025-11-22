<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TukFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'tuk_id', 'name', 'category', 'description', 'quantity', 'condition',
        'last_maintenance', 'next_maintenance', 'notes',
    ];

    protected $casts = [
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
    ];

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(Tuk::class);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    public function scopeNeedsMaintenance($query)
    {
        return $query->where('next_maintenance', '<=', now()->addDays(30));
    }
}
