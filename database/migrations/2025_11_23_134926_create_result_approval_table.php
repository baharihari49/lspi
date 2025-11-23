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
        Schema::create('result_approval', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_result_id')->constrained('assessment_results')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();

            // Approval level and sequence
            $table->integer('approval_level'); // 1 = First level, 2 = Second level, etc.
            $table->integer('sequence_order')->default(0); // Order within the same level

            $table->enum('approver_role', [
                'lead_assessor',      // Lead assessor approval
                'technical_reviewer',  // Technical expert review
                'quality_assurance',   // QA review
                'certification_manager', // Certification manager
                'director',           // Director/management
                'external_verifier'   // External verifier
            ]);

            // Approval details
            $table->enum('status', [
                'pending',
                'in_review',
                'approved',
                'rejected',
                'returned_for_revision',
                'withdrawn'
            ])->default('pending');

            $table->enum('decision', [
                'approve',
                'reject',
                'request_revision',
                'defer'
            ])->nullable();

            // Review details
            $table->text('comments')->nullable();
            $table->text('recommendations')->nullable();
            $table->json('checklist')->nullable(); // Approval checklist items
            /*
                Example structure:
                [
                    {
                        "item": "All evidence documented",
                        "checked": true,
                        "notes": "Complete"
                    },
                    {
                        "item": "Assessment standards met",
                        "checked": true,
                        "notes": "Meets all requirements"
                    }
                ]
            */

            // Issues and concerns
            $table->json('issues_identified')->nullable(); // Array of issues
            $table->json('required_changes')->nullable(); // Array of required changes
            $table->text('conditions')->nullable(); // Any conditions for approval

            // Timing
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->integer('review_duration_minutes')->nullable();

            // Due date tracking
            $table->date('due_date')->nullable();
            $table->boolean('is_overdue')->default(false);

            // Revision tracking
            $table->integer('revision_number')->default(1);
            $table->foreignId('previous_approval_id')->nullable()->constrained('result_approval')->nullOnDelete();

            // Delegation
            $table->foreignId('delegated_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('delegated_at')->nullable();
            $table->text('delegation_notes')->nullable();

            // Notification
            $table->boolean('approver_notified')->default(false);
            $table->timestamp('approver_notified_at')->nullable();

            // Metadata
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_result_id');
            $table->index('approver_id');
            $table->index('approval_level');
            $table->index('status');
            $table->index('decision');
            $table->index('due_date');
            $table->index(['assessment_result_id', 'approval_level', 'sequence_order'], 'result_approval_composite_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_approval');
    }
};
