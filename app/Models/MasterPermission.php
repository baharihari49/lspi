<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterPermission extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(MasterRole::class, 'role_permission', 'permission_id', 'role_id')
            ->withPivot('created_at');
    }
}
