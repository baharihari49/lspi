<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'registration_number',
        // Personal Information
        'full_name',
        'id_number',
        'id_type',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'marital_status',
        'nationality',
        // Contact Information
        'phone',
        'mobile',
        'email',
        // Address
        'address',
        'city',
        'province',
        'postal_code',
        // Current Employment
        'current_company',
        'current_position',
        'current_industry',
        // Emergency Contact
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
        // Profile
        'bio',
        'photo',
        // Verification
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        // Status
        'status_id',
        'is_active',
        // Management
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssesseeDocument::class);
    }

    public function employmentInfo(): HasMany
    {
        return $this->hasMany(AssesseeEmploymentInfo::class);
    }

    public function educationHistory(): HasMany
    {
        return $this->hasMany(AssesseeEducationHistory::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(AssesseeExperience::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}
