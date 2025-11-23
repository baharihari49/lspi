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
        Schema::create('assessment_units', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('scheme_unit_id')->constrained('scheme_units')->cascadeOnDelete();
            $table->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete(); // Assessor for this unit

            // Unit information (denormalized for historical record)
            $table->string('unit_code', 50);
            $table->string('unit_title');
            $table->text('unit_description')->nullable();

            // Assessment method for this specific unit
            $table->enum('assessment_method', [
                'portfolio',
                'observation',
                'interview',
                'demonstration',
                'written_test',
                'mixed'
            ])->default('mixed');

            // Unit assessment status
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'competent',
                'not_yet_competent'
            ])->default('pending');

            // Scoring
            $table->decimal('score', 5, 2)->nullable(); // Unit score percentage
            $table->integer('elements_passed')->default(0);
            $table->integer('total_elements')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);

            // Assessment result
            $table->enum('result', [
                'pending',
                'competent',
                'not_yet_competent',
                'requires_more_evidence'
            ])->default('pending');

            // Timing
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_minutes')->nullable();

            // Notes
            $table->text('assessor_notes')->nullable();
            $table->text('feedback')->nullable();
            $table->json('strengths')->nullable(); // Array of strengths
            $table->json('weaknesses')->nullable(); // Array of weaknesses
            $table->json('recommendations')->nullable(); // Array of recommendations

            // Metadata
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_id');
            $table->index('scheme_unit_id');
            $table->index('assessor_id');
            $table->index('status');
            $table->index('result');
            $table->index(['assessment_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_units');
    }
};
