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
        Schema::create('certificate_revoke', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('certificate_id')->constrained('certificates')->cascadeOnDelete();
            $table->foreignId('revoked_by')->constrained('users')->cascadeOnDelete(); // User who initiated revocation
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // User who approved revocation

            // Revocation Details
            $table->string('revocation_number')->unique(); // REV-YYYY-#### format
            $table->date('revocation_date'); // Date when revocation becomes effective
            $table->date('revocation_request_date'); // Date when revocation was requested
            $table->date('revocation_approval_date')->nullable(); // Date when revocation was approved

            // Reason
            $table->enum('revocation_reason_category', [
                'holder_request',           // Certificate holder requested revocation
                'certification_withdrawn',  // Certification withdrawn by LSP
                'fraud_misconduct',         // Fraudulent or unethical conduct
                'competency_loss',          // Loss of competency
                'non_compliance',           // Non-compliance with requirements
                'superseded',               // Replaced by new certificate
                'administrative',           // Administrative reasons
                'other'
            ]);

            $table->text('revocation_reason'); // Detailed reason for revocation
            $table->json('supporting_documents')->nullable(); // Array of document paths
            /*
                Example structure:
                [
                    {
                        "type": "complaint_letter",
                        "path": "/documents/revoke/complaint-001.pdf",
                        "uploaded_at": "2025-01-15"
                    },
                    {
                        "type": "investigation_report",
                        "path": "/documents/revoke/investigation-001.pdf",
                        "uploaded_at": "2025-01-20"
                    }
                ]
            */

            // Status
            $table->enum('status', [
                'pending',       // Awaiting approval
                'approved',      // Approved, certificate revoked
                'rejected',      // Revocation request rejected
                'appealed',      // Under appeal
                'withdrawn'      // Revocation request withdrawn
            ])->default('pending');

            // Appeal Information
            $table->boolean('is_appealable')->default(true);
            $table->date('appeal_deadline')->nullable();
            $table->date('appeal_submitted_at')->nullable();
            $table->text('appeal_reason')->nullable();
            $table->enum('appeal_status', [
                'not_filed',
                'pending',
                'accepted',
                'rejected'
            ])->default('not_filed');

            // Impact Assessment
            $table->json('affected_parties')->nullable(); // Parties affected by revocation
            $table->text('impact_notes')->nullable();

            // Notification
            $table->boolean('holder_notified')->default(false);
            $table->date('holder_notified_at')->nullable();
            $table->boolean('public_notification_required')->default(true);
            $table->date('public_notification_date')->nullable();

            // Reinstatement
            $table->boolean('is_reinstatable')->default(false);
            $table->text('reinstatement_conditions')->nullable();
            $table->date('reinstatement_eligible_from')->nullable();

            // Notes
            $table->text('internal_notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('certificate_id');
            $table->index('revocation_number');
            $table->index('revoked_by');
            $table->index('status');
            $table->index('revocation_date');
            $table->index('revocation_reason_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_revoke');
    }
};
