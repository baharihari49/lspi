<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LspProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'legal_name', 'license_number', 'license_issued_date', 'license_expiry_date',
        'accreditation_number', 'accreditation_expiry_date', 'email', 'phone', 'fax', 'website',
        'address', 'city', 'province', 'postal_code', 'logo_file_id', 'description', 'vision', 'mission',
        'director_name', 'director_position', 'director_phone', 'director_email', 'status_id', 'is_active',
    ];

    protected $casts = [
        'license_issued_date' => 'date',
        'license_expiry_date' => 'date',
        'accreditation_expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterStatus::class, 'status_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
