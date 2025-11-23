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
        Schema::create('apl02_units', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scheme_unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();

            // Unit information
            $table->string('unit_code', 50);
            $table->string('unit_title');
            $table->text('unit_description')->nullable();

            // Status & Progress
            $table->enum('status', [
                'not_started',
                'in_progress',
                'submitted',
                'under_review',
                'competent',
                'not_yet_competent',
                'completed'
            ])->default('not_started');
            $table->unsignedInteger('total_evidence')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);

            // Assessment information
            $table->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Assessment results
            $table->enum('assessment_result', [
                'pending',
                'competent',
                'not_yet_competent',
                'requires_more_evidence'
            ])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('assessment_notes')->nullable();
            $table->json('assessment_feedback')->nullable(); // Structured feedback per criterion
            $table->text('recommendations')->nullable();

            // Submission tracking
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            // Metadata
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['assessee_id', 'scheme_id']);
            $table->index(['event_id', 'status']);
            $table->index('assessor_id');
            $table->index('status');
            $table->index('assessment_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_units');
    }
};
