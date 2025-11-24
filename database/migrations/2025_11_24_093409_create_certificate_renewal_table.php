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
        Schema::create('certificate_renewal', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('original_certificate_id')->constrained('certificates')->cascadeOnDelete(); // The certificate being renewed
            $table->foreignId('new_certificate_id')->nullable()->constrained('certificates')->nullOnDelete(); // The new certificate after renewal
            $table->foreignId('assessee_id')->constrained('assessees')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete(); // User who requested renewal
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete(); // User who processed renewal

            // Renewal Details
            $table->string('renewal_number')->unique(); // REN-YYYY-#### format
            $table->date('renewal_request_date'); // Date when renewal was requested
            $table->date('renewal_due_date')->nullable(); // Recommended renewal date (before expiry)
            $table->date('renewal_processed_date')->nullable(); // Date when renewal was processed

            // Renewal Type
            $table->enum('renewal_type', [
                'standard',              // Standard renewal (re-assessment)
                'simplified',            // Simplified renewal (documentation only)
                'automatic',             // Automatic renewal (no re-assessment)
                'early_renewal',         // Renewed before expiry
                'late_renewal',          // Renewed after expiry
                'grace_period_renewal'   // Renewed within grace period
            ])->default('standard');

            // Status
            $table->enum('status', [
                'pending',               // Awaiting processing
                'in_assessment',         // Assessment in progress
                'assessment_completed',  // Assessment completed, awaiting approval
                'approved',              // Renewal approved
                'rejected',              // Renewal rejected
                'expired',               // Renewal window expired
                'cancelled',             // Renewal cancelled
                'on_hold'                // Renewal on hold
            ])->default('pending');

            // Eligibility Check
            $table->boolean('eligibility_checked')->default(false);
            $table->boolean('is_eligible')->nullable();
            $table->json('eligibility_criteria')->nullable(); // Criteria that were checked
            /*
                Example structure:
                [
                    {
                        "criterion": "Certificate not revoked",
                        "met": true
                    },
                    {
                        "criterion": "Within renewal window",
                        "met": true
                    },
                    {
                        "criterion": "No outstanding complaints",
                        "met": true
                    }
                ]
            */
            $table->text('ineligibility_reason')->nullable();

            // Assessment Requirements
            $table->boolean('requires_reassessment')->default(true);
            $table->foreignId('renewal_assessment_id')->nullable()->constrained('assessments')->nullOnDelete();
            $table->json('required_documents')->nullable(); // Documents needed for renewal
            $table->json('submitted_documents')->nullable(); // Documents submitted

            // Competency Verification
            $table->boolean('competency_verified')->default(false);
            $table->json('competency_updates')->nullable(); // Any updates to competency since last cert
            $table->text('competency_notes')->nullable();

            // CPD/Continuing Education
            $table->boolean('cpd_required')->default(false);
            $table->integer('cpd_hours_required')->nullable();
            $table->integer('cpd_hours_completed')->nullable();
            $table->json('cpd_activities')->nullable(); // CPD activities completed
            /*
                Example structure:
                [
                    {
                        "activity": "Workshop: Advanced Cataloging",
                        "date": "2024-05-15",
                        "hours": 8,
                        "provider": "National Library"
                    },
                    {
                        "activity": "Conference: Digital Libraries 2024",
                        "date": "2024-08-20",
                        "hours": 16,
                        "provider": "IFLA"
                    }
                ]
            */

            // Fees
            $table->decimal('renewal_fee', 10, 2)->nullable();
            $table->boolean('fee_paid')->default(false);
            $table->date('fee_paid_date')->nullable();
            $table->string('payment_reference')->nullable();

            // Decision
            $table->text('decision_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('conditions')->nullable(); // Conditions attached to renewal

            // Validity Extension
            $table->date('original_expiry_date'); // Original certificate expiry
            $table->date('new_expiry_date')->nullable(); // New expiry date after renewal
            $table->integer('extension_months')->nullable(); // Number of months extended
            $table->boolean('grace_period_used')->default(false);
            $table->integer('grace_period_days')->nullable();

            // Notification
            $table->boolean('renewal_reminder_sent')->default(false);
            $table->date('renewal_reminder_sent_at')->nullable();
            $table->boolean('renewal_completed_notification_sent')->default(false);

            // Notes and Metadata
            $table->text('internal_notes')->nullable();
            $table->text('notes_for_assessee')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('original_certificate_id');
            $table->index('new_certificate_id');
            $table->index('renewal_number');
            $table->index('assessee_id');
            $table->index('status');
            $table->index('renewal_type');
            $table->index('renewal_request_date');
            $table->index('renewal_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_renewal');
    }
};
