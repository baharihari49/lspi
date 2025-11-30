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
        Schema::create('apl02_forms', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('apl01_form_id')->constrained('apl01_forms')->cascadeOnDelete();
            $table->foreignId('assessee_id')->constrained('assessees')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();

            // Form identification
            $table->string('form_number')->unique(); // APL02-YYYY-XXXXX

            // Status tracking
            $table->enum('status', [
                'draft',           // Initial state, assessee filling
                'submitted',       // Submitted by assessee
                'under_review',    // Being reviewed by assessor
                'revision_required', // Needs revision
                'approved',        // Approved by assessor
                'rejected',        // Rejected
                'completed'        // Flow completed
            ])->default('draft');

            // Self-assessment by assessee (JSON array of unit assessments)
            // Structure: [{ unit_id, unit_code, unit_title, is_competent, evidence_description, notes }]
            $table->json('self_assessment')->nullable();

            // Portfolio/Evidence summary
            $table->text('portfolio_summary')->nullable(); // Overall portfolio description
            $table->json('evidence_files')->nullable(); // Array of uploaded files [{name, path, type, size, uploaded_at}]

            // Work experience relevant to scheme
            $table->json('work_experience')->nullable(); // [{company, position, duration, description, relevance}]

            // Training/Education relevant to scheme
            $table->json('training_education')->nullable(); // [{institution, program, year, certificate_number}]

            // Assessee declaration
            $table->boolean('declaration_agreed')->default(false);
            $table->timestamp('declaration_signed_at')->nullable();
            $table->text('declaration_signature')->nullable();

            // Submission tracking
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            // Review/Assessment
            $table->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('review_started_at')->nullable();
            $table->timestamp('review_completed_at')->nullable();

            // Assessor feedback
            $table->enum('assessment_result', ['pending', 'competent', 'not_yet_competent'])->default('pending');
            $table->text('assessor_notes')->nullable();
            $table->text('assessor_feedback')->nullable();
            $table->text('recommendations')->nullable();
            $table->json('revision_notes')->nullable(); // Notes for revision if needed

            // Completion tracking
            $table->integer('completion_percentage')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();

            // Auto-generated flag
            $table->boolean('auto_generated')->default(false);

            // Admin notes
            $table->text('admin_notes')->nullable();

            // Soft deletes and timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['assessee_id', 'status']);
            $table->index(['scheme_id', 'status']);
            $table->index(['assessor_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_forms');
    }
};
