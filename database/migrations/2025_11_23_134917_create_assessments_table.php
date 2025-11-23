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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            // Assessment identification
            $table->string('assessment_number', 50)->unique(); // Auto-generated: ASM-YYYY-####
            $table->string('title');
            $table->text('description')->nullable();

            // Relations
            $table->foreignId('assessee_id')->constrained('assessees')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('lead_assessor_id')->constrained('users')->cascadeOnDelete(); // Lead assessor

            // Assessment details
            $table->enum('assessment_method', [
                'portfolio',      // Portfolio assessment
                'observation',    // Direct observation
                'interview',      // Interview
                'demonstration',  // Practical demonstration
                'written_test',   // Written test
                'mixed'          // Mixed methods
            ])->default('mixed');

            $table->enum('assessment_type', [
                'initial',       // Initial assessment
                'verification',  // Verification assessment
                'surveillance',  // Surveillance assessment
                're_assessment'  // Re-assessment
            ])->default('initial');

            // Scheduling
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->string('venue')->nullable();
            $table->string('tuk_type', 50)->nullable(); // TUK type if applicable
            $table->foreignId('tuk_id')->nullable()->constrained('tuk')->nullOnDelete();

            // Status tracking
            $table->enum('status', [
                'draft',           // Draft/planning
                'scheduled',       // Scheduled
                'in_progress',     // Assessment in progress
                'completed',       // Assessment completed
                'under_review',    // Under review by lead assessor
                'verified',        // Verified
                'approved',        // Approved
                'rejected',        // Rejected
                'cancelled'        // Cancelled
            ])->default('draft');

            // Assessment period
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_minutes')->nullable(); // Actual duration
            $table->integer('planned_duration_minutes')->nullable();

            // Overall assessment result
            $table->enum('overall_result', [
                'pending',
                'competent',
                'not_yet_competent',
                'requires_more_evidence'
            ])->default('pending');

            $table->decimal('overall_score', 5, 2)->nullable(); // Overall percentage score

            // Notes and metadata
            $table->text('notes')->nullable();
            $table->text('preparation_notes')->nullable();
            $table->text('special_requirements')->nullable();
            $table->json('metadata')->nullable();

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_number');
            $table->index('assessee_id');
            $table->index('scheme_id');
            $table->index('event_id');
            $table->index('lead_assessor_id');
            $table->index('status');
            $table->index('overall_result');
            $table->index('scheduled_date');
            $table->index(['status', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
