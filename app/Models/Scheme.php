<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheme extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'occupation_title',
        'qualification_level',
        'description',
        'scheme_type',
        'sector',
        'status_id',
        'is_active',
        'effective_date',
        'review_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_date' => 'date',
        'review_date' => 'date',
    ];

    // Relationships
    public function versions(): HasMany
    {
        return $this->hasMany(SchemeVersion::class);
    }

    public function currentVersion(): HasMany
    {
        return $this->hasMany(SchemeVersion::class)->where('is_current', true);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(Apl01FormField::class);
    }

    /**
     * Get units through the current version
     */
    public function units()
    {
        // Get units from the current active version
        return $this->hasManyThrough(
            SchemeUnit::class,
            SchemeVersion::class,
            'scheme_id', // Foreign key on SchemeVersion table
            'scheme_version_id', // Foreign key on SchemeUnit table
            'id', // Local key on Scheme table
            'id' // Local key on SchemeVersion table
        )->whereHas('schemeVersion', function ($query) {
            $query->where('is_current', true);
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('scheme_type', $type);
    }

    public function scopeBySector($query, $sector)
    {
        return $query->where('sector', $sector);
    }
}
