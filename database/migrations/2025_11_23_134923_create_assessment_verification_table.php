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
        Schema::create('assessment_verification', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('assessment_unit_id')->nullable()->constrained('assessment_units')->nullOnDelete();
            $table->foreignId('verifier_id')->constrained('users')->cascadeOnDelete(); // Lead assessor or verifier

            // Verification details
            $table->string('verification_number', 50)->nullable(); // VER-####
            $table->enum('verification_level', [
                'unit_level',        // Verification of specific unit
                'assessment_level',  // Overall assessment verification
                'quality_assurance'  // QA verification
            ]);

            $table->enum('verification_type', [
                'internal',  // Internal verification by lead assessor
                'external',  // External verification/moderation
                'peer_review' // Peer review
            ]);

            // Verification checklist
            $table->json('checklist')->nullable();
            /*
                Example structure:
                [
                    {
                        "item": "Evidence is sufficient and valid",
                        "checked": true,
                        "notes": "All required evidence present"
                    },
                    {
                        "item": "Assessment decisions are consistent",
                        "checked": true,
                        "notes": "Consistent with standards"
                    }
                ]
            */

            // Verification findings
            $table->enum('verification_status', [
                'pending',
                'in_progress',
                'completed',
                'approved',
                'requires_modification',
                'rejected'
            ])->default('pending');

            $table->enum('verification_result', [
                'satisfactory',
                'needs_minor_changes',
                'needs_major_changes',
                'unsatisfactory'
            ])->nullable();

            // Detailed findings
            $table->text('findings')->nullable();
            $table->text('strengths')->nullable();
            $table->text('concerns')->nullable();
            $table->text('areas_for_improvement')->nullable();

            // Compliance check
            $table->boolean('meets_standards')->nullable();
            $table->boolean('evidence_sufficient')->nullable();
            $table->boolean('assessment_fair')->nullable();
            $table->boolean('documentation_complete')->nullable();

            // Required actions
            $table->json('required_actions')->nullable(); // Array of required actions
            $table->json('recommendations')->nullable(); // Array of recommendations

            // Timing
            $table->timestamp('verified_at')->nullable();
            $table->integer('verification_duration_minutes')->nullable();

            // Notes
            $table->text('verifier_notes')->nullable();
            $table->text('assessor_response')->nullable(); // Response from original assessor

            // Metadata
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_id');
            $table->index('assessment_unit_id');
            $table->index('verifier_id');
            $table->index('verification_status');
            $table->index('verification_level');
            $table->index('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_verification');
    }
};
