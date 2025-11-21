<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterRole extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    /**
     * Get the permissions for this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(MasterPermission::class, 'role_permission', 'role_id', 'permission_id')
            ->withPivot('created_at');
    }

    /**
     * Get the users for this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id')
            ->withPivot('assigned_by', 'assigned_at', 'revoked_at')
            ->wherePivotNull('revoked_at');
    }
}
