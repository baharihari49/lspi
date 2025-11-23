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
        Schema::create('assessment_criteria', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_unit_id')->constrained('assessment_units')->cascadeOnDelete();
            $table->foreignId('scheme_element_id')->constrained('scheme_elements')->cascadeOnDelete();

            // Element information (denormalized for historical record)
            $table->string('element_code', 50);
            $table->string('element_title');

            // Assessment method for this criterion
            $table->enum('assessment_method', [
                'portfolio',
                'observation',
                'interview',
                'demonstration',
                'written_test',
                'mixed'
            ])->nullable();

            // Scoring
            $table->enum('result', [
                'pending',
                'competent',
                'not_yet_competent',
                'not_assessed'
            ])->default('pending');

            $table->decimal('score', 5, 2)->nullable(); // Score for this criterion
            $table->boolean('is_critical')->default(false); // Is this a critical criterion?

            // Evidence and observations
            $table->text('evidence_observed')->nullable(); // What evidence was observed
            $table->text('assessor_notes')->nullable();
            $table->text('feedback')->nullable();

            // Assessment details
            $table->timestamp('assessed_at')->nullable();
            $table->foreignId('assessed_by')->nullable()->constrained('users')->nullOnDelete();

            // Metadata
            $table->json('metadata')->nullable();
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_unit_id');
            $table->index('scheme_element_id');
            $table->index('result');
            $table->index(['assessment_unit_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_criteria');
    }
};
