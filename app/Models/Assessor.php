<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_number', 'met_number', 'user_id', 'full_name', 'id_card_number',
        'birth_date', 'birth_place', 'gender', 'address', 'city', 'province', 'postal_code',
        'phone', 'mobile', 'email', 'education_level', 'major', 'institution',
        'occupation', 'company', 'position', 'registration_date', 'valid_until',
        'registration_status', 'photo_file_id', 'verification_status', 'verification_notes',
        'verified_by', 'verified_at', 'is_active', 'status_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'date',
        'valid_until' => 'date',
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'photo_file_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssessorDocument::class);
    }

    public function competencyScopes(): HasMany
    {
        return $this->hasMany(AssessorCompetencyScope::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(AssessorExperience::class);
    }

    public function bankInfo(): HasMany
    {
        return $this->hasMany(AssessorBankInfo::class);
    }

    public function primaryBankInfo(): HasMany
    {
        return $this->hasMany(AssessorBankInfo::class)->where('is_primary', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('registration_status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('valid_until', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now())
                    ->orWhere('registration_status', 'expired');
    }
}
