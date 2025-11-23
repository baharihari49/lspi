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
        Schema::create('assessment_feedback', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('assessment_unit_id')->nullable()->constrained('assessment_units')->nullOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete(); // Who provided feedback
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete(); // Assessee receiving feedback

            // Feedback details
            $table->string('feedback_number', 50)->nullable(); // FB-####
            $table->string('title')->nullable();

            $table->enum('feedback_type', [
                'formative',      // During assessment (developmental)
                'summative',      // Final assessment feedback
                'interim',        // Progress feedback
                'corrective',     // Corrective feedback for improvements
                'recognition'     // Positive recognition
            ]);

            $table->enum('feedback_method', [
                'written',
                'verbal',
                'face_to_face',
                'video',
                'mixed'
            ])->default('written');

            // Feedback content
            $table->text('positive_aspects')->nullable(); // What went well
            $table->text('areas_for_improvement')->nullable(); // What needs improvement
            $table->text('specific_examples')->nullable(); // Specific examples to illustrate points
            $table->text('recommendations')->nullable(); // Specific recommendations

            // Competency-based feedback
            $table->json('strengths')->nullable(); // Array of strengths
            $table->json('weaknesses')->nullable(); // Array of weaknesses
            $table->json('development_areas')->nullable(); // Areas for development

            // Action plan
            $table->json('action_items')->nullable(); // Specific actions to take
            /*
                Example structure:
                [
                    {
                        "action": "Review safety procedures",
                        "priority": "high",
                        "due_date": "2025-12-01",
                        "resources": "Safety manual, online training",
                        "completed": false
                    }
                ]
            */

            // Timing and delivery
            $table->timestamp('provided_at')->nullable();
            $table->timestamp('delivered_at')->nullable(); // When feedback was actually delivered
            $table->boolean('is_confidential')->default(false);

            // Recipient response
            $table->text('recipient_acknowledgment')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('recipient_comments')->nullable();

            // Follow-up
            $table->boolean('requires_follow_up')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->timestamp('followed_up_at')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_id');
            $table->index('assessment_unit_id');
            $table->index('provider_id');
            $table->index('recipient_id');
            $table->index('feedback_type');
            $table->index('provided_at');
            $table->index(['assessment_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_feedback');
    }
};
