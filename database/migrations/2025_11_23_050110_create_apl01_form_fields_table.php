<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apl01_form_fields', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();

            // Field Configuration
            $table->string('field_name')->comment('Unique field identifier');
            $table->string('field_label')->comment('Display label for field');
            $table->text('field_description')->nullable()->comment('Help text/description');

            // Field Type
            $table->enum('field_type', [
                'text',           // Single line text input
                'textarea',       // Multi-line text
                'number',         // Numeric input
                'email',          // Email input
                'date',           // Date picker
                'select',         // Dropdown select
                'radio',          // Radio buttons
                'checkbox',       // Single checkbox
                'checkboxes',     // Multiple checkboxes
                'file',           // File upload
                'image',          // Image upload
                'url',            // URL input
                'phone',          // Phone number
                'rating',         // Rating scale (1-5, etc)
                'yesno',          // Yes/No radio
                'section_header', // Section heading (not input)
                'html'            // Custom HTML content
            ])->default('text');

            // Field Options (for select, radio, checkboxes)
            $table->json('field_options')->nullable()->comment('Array of options: [{value, label}]');

            // Validation Rules (JSON)
            $table->json('validation_rules')->nullable()->comment('Laravel validation rules as JSON');

            // Field Behavior
            $table->boolean('is_required')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_visible')->default(true);

            // Conditional Logic (show/hide based on other fields)
            $table->json('conditional_logic')->nullable()->comment('Rules for showing/hiding field');

            // Default Value
            $table->string('default_value')->nullable();
            $table->string('placeholder')->nullable();

            // File Upload Configuration (for file/image types)
            $table->json('file_config')->nullable()->comment('Max size, allowed types, etc');

            // Display Configuration
            $table->string('css_class')->nullable()->comment('Custom CSS classes');
            $table->string('wrapper_class')->nullable()->comment('Wrapper CSS classes');
            $table->integer('field_width')->default(100)->comment('Width in percentage (50, 100)');

            // Grouping
            $table->string('section')->nullable()->comment('Section/group name');
            $table->integer('order')->default(0)->comment('Display order within section');

            // Help & Guidance
            $table->text('help_text')->nullable();
            $table->string('tooltip')->nullable();

            // Metadata
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['scheme_id', 'section', 'order']);
            $table->index('field_type');
            $table->unique(['scheme_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl01_form_fields');
    }
};
