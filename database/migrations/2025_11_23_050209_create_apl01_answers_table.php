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
        Schema::create('apl01_answers', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('apl01_form_id')->constrained('apl01_forms')->cascadeOnDelete();
            $table->foreignId('form_field_id')->constrained('apl01_form_fields')->cascadeOnDelete();

            // Answer Data
            $table->text('answer_value')->nullable()->comment('Text/string answer value');
            $table->json('answer_json')->nullable()->comment('JSON answer for complex fields');
            $table->string('answer_file')->nullable()->comment('File path for file uploads');

            // For multiple files (checkboxes with files, multiple file uploads)
            $table->json('answer_files')->nullable()->comment('Array of file paths');

            // Validation Status
            $table->boolean('is_valid')->default(true)->comment('Passed validation');
            $table->text('validation_errors')->nullable()->comment('Validation error messages');

            // Review/Verification
            $table->enum('review_status', ['pending', 'approved', 'rejected', 'needs_clarification'])->default('pending');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            // Metadata
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['apl01_form_id', 'form_field_id']);
            $table->index('review_status');
            $table->unique(['apl01_form_id', 'form_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl01_answers');
    }
};
