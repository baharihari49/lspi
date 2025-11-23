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
        Schema::create('apl01_forms', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('assessee_id')->constrained('assessees')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();

            // Form Identification
            $table->string('form_number')->unique()->comment('APL-01-YYYY-####');
            $table->date('submission_date')->nullable();

            // Personal Data (can be auto-filled from assessee)
            $table->string('full_name');
            $table->string('id_number')->comment('NIK/Passport');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();

            // Employment Information
            $table->string('current_company')->nullable();
            $table->string('current_position')->nullable();
            $table->string('current_industry')->nullable();

            // Purpose of Certification
            $table->text('certification_purpose')->nullable()->comment('Why seeking certification');
            $table->text('target_competency')->nullable()->comment('Competency units being certified');

            // Self Assessment
            $table->json('self_assessment')->nullable()->comment('JSON of competency self-assessment');

            // Supporting Documents (JSON array of file paths)
            $table->json('supporting_documents')->nullable()->comment('Array of document info');

            // Declaration
            $table->boolean('declaration_agreed')->default(false);
            $table->timestamp('declaration_signed_at')->nullable();
            $table->string('declaration_signature')->nullable()->comment('Digital signature file path');

            // Status Workflow
            $table->enum('status', [
                'draft',           // Being filled by assessee
                'submitted',       // Submitted for review
                'under_review',    // Being reviewed
                'approved',        // Approved by reviewer
                'rejected',        // Rejected - need revision
                'revised',         // Revised by assessee
                'completed',       // All process completed
                'cancelled'        // Cancelled
            ])->default('draft');

            // Submission tracking
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            // Completion tracking
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();

            // Review tracking
            $table->integer('current_review_level')->default(0)->comment('Current review level');
            $table->foreignId('current_reviewer_id')->nullable()->constrained('users')->nullOnDelete();

            // Notes and Feedback
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();

            // Metadata
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('form_number');
            $table->index('status');
            $table->index('submission_date');
            $table->index(['assessee_id', 'scheme_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl01_forms');
    }
};
