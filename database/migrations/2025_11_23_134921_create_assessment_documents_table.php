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
        Schema::create('assessment_documents', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('assessment_unit_id')->nullable()->constrained('assessment_units')->nullOnDelete();
            $table->foreignId('assessment_criteria_id')->nullable()->constrained('assessment_criteria')->nullOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();

            // Document details
            $table->string('document_number', 50)->nullable(); // DOC-####
            $table->string('title');
            $table->text('description')->nullable();

            // Document type and category
            $table->enum('document_type', [
                'evidence',           // Evidence document
                'supporting_document', // Supporting documentation
                'work_sample',        // Work sample
                'certificate',        // Certificate/credential
                'photo',             // Photo evidence
                'video',             // Video evidence
                'audio',             // Audio recording
                'form',              // Filled form/checklist
                'report',            // Assessment report
                'other'
            ]);

            $table->enum('evidence_type', [
                'direct',    // Direct evidence (work samples, observations)
                'indirect',  // Indirect evidence (certificates, testimonials)
                'supplementary' // Supplementary evidence
            ])->nullable();

            // File information
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type', 100); // MIME type
            $table->bigInteger('file_size'); // Size in bytes
            $table->string('original_filename')->nullable();

            // Verification status
            $table->enum('verification_status', [
                'pending',
                'verified',
                'rejected',
                'requires_clarification'
            ])->default('pending');

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            // Relevance assessment
            $table->enum('relevance', [
                'highly_relevant',
                'relevant',
                'partially_relevant',
                'not_relevant'
            ])->nullable();

            $table->text('assessor_comments')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_id');
            $table->index('assessment_unit_id');
            $table->index('assessment_criteria_id');
            $table->index('uploaded_by');
            $table->index('document_type');
            $table->index('verification_status');
            $table->index(['assessment_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_documents');
    }
};
