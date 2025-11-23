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
        Schema::create('apl02_evidence', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('apl02_unit_id')->constrained()->cascadeOnDelete();
            $table->string('evidence_number', 50)->unique(); // Auto-generated: EVD-YYYY-####

            // Evidence information
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('evidence_type', [
                'document',
                'certificate',
                'work_sample',
                'project',
                'photo',
                'video',
                'presentation',
                'log_book',
                'portfolio',
                'other'
            ])->default('document');

            // File information
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type', 50)->nullable(); // MIME type
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes
            $table->string('original_filename')->nullable();

            // External links (if evidence is a URL)
            $table->string('external_url', 500)->nullable();

            // Dates
            $table->date('evidence_date')->nullable(); // Date when evidence was created/obtained
            $table->date('validity_start_date')->nullable();
            $table->date('validity_end_date')->nullable();

            // Issuing information (for certificates)
            $table->string('issued_by')->nullable();
            $table->string('issuer_organization')->nullable();
            $table->string('certificate_number')->nullable();

            // Verification & Validation
            $table->enum('verification_status', [
                'pending',
                'verified',
                'rejected',
                'requires_clarification'
            ])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            // Assessment
            $table->enum('assessment_result', [
                'pending',
                'valid',
                'invalid',
                'insufficient'
            ])->default('pending');
            $table->decimal('relevance_score', 5, 2)->nullable(); // 0-100
            $table->text('assessor_notes')->nullable();

            // Status & Flags
            $table->boolean('is_authentic')->default(true);
            $table->boolean('is_current')->default(true);
            $table->boolean('is_sufficient')->default(false);
            $table->boolean('is_public')->default(false); // Can be viewed by others
            $table->boolean('is_primary')->default(false); // Main evidence for unit

            // Submission tracking
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            // Metadata
            $table->json('metadata')->nullable(); // Additional custom fields
            $table->text('notes')->nullable();
            $table->unsignedInteger('view_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['assessee_id', 'apl02_unit_id']);
            $table->index('evidence_type');
            $table->index('verification_status');
            $table->index('assessment_result');
            $table->index('evidence_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_evidence');
    }
};
