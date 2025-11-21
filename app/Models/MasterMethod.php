<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMethod extends Model
{
    protected $fillable = [
        'category',
        'code',
        'name',
        'description',
    ];

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
