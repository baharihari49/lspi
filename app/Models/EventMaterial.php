<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id', 'event_session_id', 'title', 'description', 'material_type',
        'file_path', 'file_name', 'file_type', 'file_size',
        'is_public', 'is_downloadable', 'published_at', 'download_count',
        'order', 'uploaded_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_downloadable' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
