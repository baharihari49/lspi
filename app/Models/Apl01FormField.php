<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apl01FormField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'scheme_id',
        'field_name',
        'field_label',
        'field_description',
        'field_type',
        'field_options',
        'validation_rules',
        'is_required',
        'is_enabled',
        'is_visible',
        'conditional_logic',
        'default_value',
        'placeholder',
        'file_config',
        'css_class',
        'wrapper_class',
        'field_width',
        'section',
        'order',
        'help_text',
        'tooltip',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'field_options' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'is_enabled' => 'boolean',
        'is_visible' => 'boolean',
        'conditional_logic' => 'array',
        'file_config' => 'array',
        'field_width' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Apl01Answer::class, 'form_field_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeByScheme($query, $schemeId)
    {
        return $query->where('scheme_id', $schemeId);
    }

    public function scopeOrderedBySectionAndOrder($query)
    {
        return $query->orderBy('section')->orderBy('order');
    }

    // Accessors
    public function getFieldTypeLabelAttribute()
    {
        $labels = [
            'text' => 'Text Input',
            'textarea' => 'Text Area',
            'number' => 'Number',
            'email' => 'Email',
            'date' => 'Date',
            'select' => 'Dropdown Select',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkbox',
            'checkboxes' => 'Multiple Checkboxes',
            'file' => 'File Upload',
            'image' => 'Image Upload',
            'url' => 'URL',
            'phone' => 'Phone Number',
            'rating' => 'Rating',
            'yesno' => 'Yes/No',
            'section_header' => 'Section Header',
            'html' => 'HTML Content',
        ];

        return $labels[$this->field_type] ?? ucfirst($this->field_type);
    }

    public function getIsFileUploadAttribute()
    {
        return in_array($this->field_type, ['file', 'image']);
    }

    public function getIsMultipleChoiceAttribute()
    {
        return in_array($this->field_type, ['select', 'radio', 'checkboxes']);
    }

    public function getHasOptionsAttribute()
    {
        return in_array($this->field_type, ['select', 'radio', 'checkbox', 'checkboxes']);
    }

    public function getMaxFileSizeAttribute()
    {
        return $this->file_config['max_size'] ?? 10240; // Default 10MB in KB
    }

    public function getAllowedFileTypesAttribute()
    {
        return $this->file_config['allowed_types'] ?? [];
    }
}
