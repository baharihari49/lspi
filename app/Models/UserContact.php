<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserContact extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    /**
     * Get the user that owns the contact.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
