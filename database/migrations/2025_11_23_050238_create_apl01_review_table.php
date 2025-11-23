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
        Schema::create('apl01_review', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->foreignId('apl01_form_id')->constrained('apl01_forms')->cascadeOnDelete();

            // Review Level
            $table->integer('review_level')->default(1)->comment('Level 1, 2, 3, etc.');
            $table->string('review_level_name')->nullable()->comment('e.g., Initial Review, Manager Review, Final Approval');

            // Reviewer
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('reviewer_role')->nullable()->comment('Role at time of review');

            // Review Decision
            $table->enum('decision', [
                'pending',              // Awaiting review
                'approved',             // Approved - move to next level
                'rejected',             // Rejected - need revision
                'approved_with_notes',  // Approved but with comments
                'returned',             // Return to previous level
                'forwarded',            // Forward to another reviewer
                'on_hold'               // Put on hold
            ])->default('pending');

            // Review Details
            $table->text('review_notes')->nullable()->comment('Reviewer comments/feedback');
            $table->json('checklist_items')->nullable()->comment('Review checklist with status');
            $table->integer('score')->nullable()->comment('Optional scoring (1-100)');

            // Specific Feedback per Section/Field
            $table->json('field_feedback')->nullable()->comment('Feedback per form field');

            // Attachments & Evidence
            $table->json('attachments')->nullable()->comment('Supporting documents from reviewer');

            // Timestamps
            $table->timestamp('assigned_at')->nullable()->comment('When assigned to reviewer');
            $table->timestamp('started_at')->nullable()->comment('When reviewer started review');
            $table->timestamp('completed_at')->nullable()->comment('When review completed');
            $table->timestamp('deadline')->nullable()->comment('Review deadline');

            // Duration Tracking
            $table->integer('review_duration')->nullable()->comment('Duration in minutes');

            // Escalation
            $table->boolean('is_escalated')->default(false);
            $table->text('escalation_reason')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->foreignId('escalated_to')->nullable()->constrained('users')->nullOnDelete();

            // Notification Status
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notification_sent_at')->nullable();

            // Status Tracking
            $table->boolean('is_current')->default(true)->comment('Is this the current active review');
            $table->boolean('is_final')->default(false)->comment('Is this the final review level');

            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['apl01_form_id', 'review_level']);
            $table->index(['reviewer_id', 'decision']);
            $table->index('is_current');
            $table->index('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl01_review');
    }
};
