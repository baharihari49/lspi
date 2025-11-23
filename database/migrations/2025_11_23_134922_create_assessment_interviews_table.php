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
        Schema::create('assessment_interviews', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_unit_id')->constrained('assessment_units')->cascadeOnDelete();
            $table->foreignId('assessment_criteria_id')->nullable()->constrained('assessment_criteria')->nullOnDelete();
            $table->foreignId('interviewer_id')->constrained('users')->cascadeOnDelete(); // Assessor conducting interview
            $table->foreignId('interviewee_id')->constrained('users')->cascadeOnDelete(); // Assessee being interviewed

            // Interview details
            $table->string('interview_number', 50)->nullable(); // INT-####
            $table->string('session_title');
            $table->text('purpose')->nullable(); // Purpose of the interview

            // Timing and location
            $table->timestamp('conducted_at');
            $table->integer('duration_minutes')->nullable();
            $table->string('location')->nullable();
            $table->enum('interview_method', [
                'face_to_face',
                'video_conference',
                'phone',
                'written'
            ])->default('face_to_face');

            // Interview structure
            $table->json('questions')->nullable(); // Array of question-answer pairs
            /*
                Example structure:
                [
                    {
                        "question": "Describe your experience with...",
                        "answer": "I have worked on...",
                        "competency_area": "Technical Knowledge",
                        "satisfactory": true,
                        "notes": "Clear understanding demonstrated"
                    }
                ]
            */

            // Assessment
            $table->text('key_findings')->nullable();
            $table->text('competencies_demonstrated')->nullable();
            $table->text('gaps_identified')->nullable();

            $table->enum('overall_assessment', [
                'fully_satisfactory',
                'satisfactory',
                'needs_improvement',
                'unsatisfactory'
            ])->nullable();

            $table->decimal('score', 5, 2)->nullable();

            // Notes and observations
            $table->text('interviewer_notes')->nullable();
            $table->text('behavioral_observations')->nullable(); // Communication skills, confidence, etc.
            $table->text('technical_observations')->nullable(); // Technical knowledge demonstrated

            // Follow-up
            $table->boolean('requires_follow_up')->default(false);
            $table->text('follow_up_items')->nullable();

            // Documentation
            $table->string('recording_file_path')->nullable(); // Path to audio/video recording if any
            $table->text('transcript')->nullable(); // Interview transcript if available

            // Metadata
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_unit_id');
            $table->index('assessment_criteria_id');
            $table->index('interviewer_id');
            $table->index('interviewee_id');
            $table->index('conducted_at');
            $table->index(['assessment_unit_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_interviews');
    }
};
