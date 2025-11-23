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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('assessee_id')->constrained('assessees')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();

            // Result identification
            $table->string('result_number', 50)->unique(); // RES-YYYY-####
            $table->string('certificate_number', 50)->nullable()->unique(); // If competent

            // Overall result
            $table->enum('final_result', [
                'competent',
                'not_yet_competent',
                'requires_reassessment'
            ]);

            // Detailed scoring
            $table->decimal('overall_score', 5, 2)->nullable(); // Percentage
            $table->integer('units_assessed')->default(0);
            $table->integer('units_competent')->default(0);
            $table->integer('units_not_yet_competent')->default(0);

            $table->integer('total_criteria')->default(0);
            $table->integer('criteria_met')->default(0);
            $table->decimal('criteria_percentage', 5, 2)->default(0);

            // Critical criteria tracking
            $table->integer('critical_criteria_total')->default(0);
            $table->integer('critical_criteria_met')->default(0);
            $table->boolean('all_critical_criteria_met')->default(false);

            // Unit breakdown
            $table->json('unit_results')->nullable();
            /*
                Example structure:
                [
                    {
                        "unit_id": 1,
                        "unit_code": "J.620100.001.02",
                        "unit_title": "Mengoperasikan Software Spreadsheet",
                        "result": "competent",
                        "score": 92.5,
                        "criteria_met": 18,
                        "total_criteria": 20
                    }
                ]
            */

            // Assessment summary
            $table->text('executive_summary')->nullable();
            $table->json('key_strengths')->nullable(); // Array of key strengths
            $table->json('development_areas')->nullable(); // Array of development areas
            $table->text('overall_performance_notes')->nullable();

            // Evidence summary
            $table->integer('documents_submitted')->default(0);
            $table->integer('observations_conducted')->default(0);
            $table->integer('interviews_conducted')->default(0);
            $table->text('evidence_summary')->nullable();

            // Recommendations
            $table->json('recommendations')->nullable(); // Array of recommendations
            $table->text('next_steps')->nullable();
            $table->text('reassessment_plan')->nullable(); // If not yet competent

            // Certification details (if competent)
            $table->date('certification_date')->nullable();
            $table->date('certification_expiry_date')->nullable();
            $table->string('certification_level')->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->timestamp('certificate_issued_at')->nullable();

            // Assessors involved
            $table->foreignId('lead_assessor_id')->constrained('users')->cascadeOnDelete();
            $table->json('contributing_assessors')->nullable(); // Array of assessor IDs

            // Approval tracking
            $table->enum('approval_status', [
                'pending',
                'approved',
                'rejected',
                'requires_revision'
            ])->default('pending');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();

            // Publication
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();

            // Notification tracking
            $table->boolean('assessee_notified')->default(false);
            $table->timestamp('assessee_notified_at')->nullable();

            // Result validity
            $table->boolean('is_valid')->default(true);
            $table->text('invalidation_reason')->nullable();
            $table->timestamp('invalidated_at')->nullable();

            // Metadata
            $table->json('metadata')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('assessment_id');
            $table->index('assessee_id');
            $table->index('scheme_id');
            $table->index('result_number');
            $table->index('certificate_number');
            $table->index('final_result');
            $table->index('approval_status');
            $table->index('certification_date');
            $table->index('certification_expiry_date');
            $table->index(['assessee_id', 'scheme_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};
