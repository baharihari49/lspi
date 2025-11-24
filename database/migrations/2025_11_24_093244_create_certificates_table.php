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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('assessment_result_id')->constrained('assessment_results')->cascadeOnDelete();
            $table->foreignId('assessee_id')->constrained('assessees')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();

            // Certificate Identification
            $table->string('certificate_number')->unique(); // CERT-YYYY-#### format
            $table->string('registration_number')->unique()->nullable(); // BNSP registration number
            $table->string('qr_code')->unique(); // Unique QR code identifier
            $table->string('verification_url')->unique(); // Public verification URL

            // Certificate Status
            $table->enum('status', [
                'active',
                'expired',
                'revoked',
                'suspended',
                'renewed'
            ])->default('active');

            // Certificate Details
            $table->string('holder_name'); // Full name as printed on certificate
            $table->string('holder_id_number')->nullable(); // ID/KTP number
            $table->text('competency_achieved'); // Description of competency
            $table->json('unit_codes')->nullable(); // Array of unit codes achieved
            /*
                Example structure:
                [
                    "J.620100.001.01",
                    "J.620100.002.01",
                    "J.620100.003.01"
                ]
            */

            // Scheme Information (denormalized for certificate printing)
            $table->string('scheme_name');
            $table->string('scheme_code');
            $table->string('scheme_level')->nullable(); // e.g., "Level 3", "Advanced"

            // Issuing Organization Info (denormalized)
            $table->string('issuing_organization')->default('LSP-PIE'); // LSP name
            $table->string('issuing_organization_license')->nullable(); // LSP license number
            $table->text('issuing_organization_address')->nullable();

            // Dates
            $table->date('assessment_date')->nullable(); // Date of assessment completion
            $table->date('issue_date'); // Certificate issue date
            $table->date('valid_from'); // Certificate validity start date
            $table->date('valid_until'); // Certificate expiry date
            $table->integer('validity_period_months')->default(36); // Usually 3 years (36 months)

            // Signatories
            $table->json('signatories')->nullable(); // Array of signatory information
            /*
                Example structure:
                [
                    {
                        "name": "Dr. John Doe",
                        "position": "Direktur LSP-PIE",
                        "signature_path": "/signatures/director.png"
                    },
                    {
                        "name": "Jane Smith",
                        "position": "Manager Sertifikasi",
                        "signature_path": "/signatures/manager.png"
                    }
                ]
            */

            // Template and Design
            $table->string('template_name')->default('default'); // Certificate template to use
            $table->string('language')->default('id'); // id, en
            $table->json('custom_fields')->nullable(); // Additional custom fields for certificate

            // File Storage
            $table->string('pdf_path')->nullable(); // Path to generated PDF certificate
            $table->string('pdf_url')->nullable(); // Public URL to certificate PDF
            $table->integer('pdf_file_size')->nullable(); // File size in bytes
            $table->timestamp('pdf_generated_at')->nullable();

            // QR Code
            $table->string('qr_code_path')->nullable(); // Path to QR code image
            $table->integer('qr_scan_count')->default(0); // Number of times QR code was scanned

            // Verification
            $table->boolean('is_verified')->default(true); // Certificate authenticity
            $table->boolean('is_public')->default(true); // Publicly visible/searchable
            $table->integer('verification_count')->default(0); // Number of times certificate was verified online

            // Renewal Information
            $table->foreignId('renewed_from_certificate_id')->nullable()->constrained('certificates')->nullOnDelete();
            $table->foreignId('renewed_by_certificate_id')->nullable()->constrained('certificates')->nullOnDelete();
            $table->boolean('is_renewable')->default(true);
            $table->date('renewal_notification_sent_at')->nullable();

            // Revocation Information (if status is revoked)
            $table->date('revoked_at')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('revocation_reason')->nullable();

            // Notes and Remarks
            $table->text('notes')->nullable(); // Internal notes
            $table->text('special_conditions')->nullable(); // Special conditions or limitations
            $table->json('metadata')->nullable(); // Additional metadata

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('certificate_number');
            $table->index('registration_number');
            $table->index('qr_code');
            $table->index('assessee_id');
            $table->index('scheme_id');
            $table->index('status');
            $table->index('issue_date');
            $table->index('valid_until');
            $table->index(['status', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
