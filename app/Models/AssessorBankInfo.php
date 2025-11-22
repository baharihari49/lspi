<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessorBankInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assessor_bank_info';

    protected $fillable = [
        'assessor_id', 'bank_name', 'bank_code', 'branch_name', 'account_number',
        'account_holder_name', 'npwp_number', 'tax_name', 'tax_address',
        'verification_status', 'verification_notes', 'verified_by', 'verified_at',
        'bank_statement_file_id', 'npwp_file_id', 'is_primary', 'is_active',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(Assessor::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function bankStatementFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'bank_statement_file_id');
    }

    public function npwpFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'npwp_file_id');
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }
}
