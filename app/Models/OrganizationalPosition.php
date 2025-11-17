<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalPosition extends Model
{
    protected $fillable = [
        'name',
        'position',
        'level',
        'parent_id',
        'order',
        'photo',
        'email',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: Parent position
    public function parent()
    {
        return $this->belongsTo(OrganizationalPosition::class, 'parent_id');
    }

    // Relationship: Children positions
    public function children()
    {
        return $this->hasMany(OrganizationalPosition::class, 'parent_id')->orderBy('order');
    }

    // Scope: Active positions only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Root level positions (no parent)
    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    // Scope: By level
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level)->orderBy('order');
    }
}
