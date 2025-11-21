<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatus extends Model
{
    protected $fillable = [
        'category',
        'code',
        'label',
        'description',
        'sort_order',
    ];

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category)->orderBy('sort_order');
    }

    /**
     * Scope to get status by category and code.
     */
    public function scopeByCategoryCode($query, string $category, string $code)
    {
        return $query->where('category', $category)->where('code', $code);
    }
}
