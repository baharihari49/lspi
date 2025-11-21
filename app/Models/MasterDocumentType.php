<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDocumentType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'retention_months',
    ];
}
