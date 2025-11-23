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
        Schema::create('assessment_observations', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_unit_id')->constrained('assessment_units')->cascadeOnDelete();
            $table->foreignId('assessment_criteria_id')->nullable()->constrained('assessment_criteria')->nullOnDelete();
            $table->foreignId('observer_id')->constrained('users')->cascadeOnDelete(); // Assessor who observed

            // Observation details
            $table->string('observation_number', 50)->nullable(); // OBS-####
            $table->string('activity_observed'); // What activity was being observed
            $table->text('context')->nullable(); // Context of the observation

            // Timing
            $table->timestamp('observed_at');
            $table->integer('duration_minutes')->nullable();
            $table->string('location')->nullable(); // Where observation took place

            // Observation findings
            $table->text('what_was_observed'); // Detailed description
            $table->text('performance_indicators')->nullable(); // Which indicators were met
            $table->text('evidence_collected')->nullable(); // Evidence gathered

            // Assessment
            $table->enum('competency_demonstrated', [
                'fully_competent',
                'partially_competent',
                'not_competent',
                'not_applicable'
            ])->default('not_applicable');

            $table->decimal('score', 5, 2)->nullable();
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('observer_notes')->nullable();

            // Follow-up
            $table->boolean('requires_follow_up')->default(false);
            $table->text('follow_up_notes')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_unit_id');
            $table->index('assessment_criteria_id');
            $table->index('observer_id');
            $table->index('observed_at');
            $table->index(['assessment_unit_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_observations');
    }
};
