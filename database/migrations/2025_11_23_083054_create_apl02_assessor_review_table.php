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
        Schema::create('apl02_assessor_review', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('apl02_unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained('users')->cascadeOnDelete();

            // Review information
            $table->string('review_number', 50)->unique(); // Auto-generated: REV-APL02-YYYY-####
            $table->enum('review_type', [
                'initial_review',
                'verification',
                'validation',
                'final_assessment',
                're_assessment'
            ])->default('initial_review');

            // Assessment decision
            $table->enum('decision', [
                'competent',
                'not_yet_competent',
                'pending',
                'requires_more_evidence',
                'requires_demonstration',
                'deferred'
            ])->default('pending');

            // Detailed assessment
            $table->text('overall_comments')->nullable();
            $table->json('element_assessments')->nullable(); // Per-element detailed assessment
            $table->json('evidence_assessments')->nullable(); // Per-evidence assessment

            // Scoring (VATUK - Valid, Authentic, Terkini/Current, Utuh/Complete, Konsisten/Consistent)
            $table->decimal('validity_score', 5, 2)->nullable();      // 0-100
            $table->decimal('authenticity_score', 5, 2)->nullable();  // 0-100
            $table->decimal('currency_score', 5, 2)->nullable();      // 0-100
            $table->decimal('sufficiency_score', 5, 2)->nullable();   // 0-100
            $table->decimal('consistency_score', 5, 2)->nullable();   // 0-100
            $table->decimal('overall_score', 5, 2)->nullable();       // Average of all scores

            // Recommendations
            $table->text('recommendations')->nullable();
            $table->json('improvement_areas')->nullable(); // Areas that need improvement
            $table->text('additional_evidence_required')->nullable();
            $table->json('next_steps')->nullable();

            // Strengths & Weaknesses
            $table->json('strengths')->nullable();
            $table->json('weaknesses')->nullable();

            // Interview/Demonstration notes
            $table->boolean('requires_interview')->default(false);
            $table->boolean('requires_demonstration')->default(false);
            $table->text('interview_notes')->nullable();
            $table->text('demonstration_notes')->nullable();
            $table->timestamp('interview_conducted_at')->nullable();
            $table->timestamp('demonstration_conducted_at')->nullable();

            // Timeline
            $table->timestamp('review_started_at')->nullable();
            $table->timestamp('review_completed_at')->nullable();
            $table->unsignedInteger('review_duration_minutes')->nullable(); // Time spent on review
            $table->date('deadline')->nullable();

            // Status tracking
            $table->enum('status', [
                'draft',
                'in_progress',
                'completed',
                'submitted',
                'approved',
                'revision_required'
            ])->default('draft');

            // Verification by lead assessor
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            // Flags
            $table->boolean('is_final')->default(false);
            $table->boolean('is_passed')->default(false);
            $table->boolean('requires_re_assessment')->default(false);
            $table->date('re_assessment_date')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('internal_notes')->nullable(); // Not visible to assessee

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['apl02_unit_id', 'assessor_id']);
            $table->index('review_type');
            $table->index('decision');
            $table->index('status');
            $table->index(['is_final', 'is_passed']);
            $table->index('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_assessor_review');
    }
};
