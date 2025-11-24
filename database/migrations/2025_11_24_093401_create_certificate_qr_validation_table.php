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
        Schema::create('certificate_qr_validation', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('certificate_id')->constrained('certificates')->cascadeOnDelete();

            // Scan Information
            $table->string('scan_id')->unique(); // Unique scan identifier
            $table->timestamp('scanned_at'); // When QR code was scanned
            $table->string('scan_method')->default('qr_code'); // qr_code, manual_entry, api

            // Validation Result
            $table->enum('validation_status', [
                'valid',
                'expired',
                'revoked',
                'invalid',
                'not_found'
            ])->default('valid');

            // Scanner Information
            $table->string('scanner_ip')->nullable(); // IP address of scanner
            $table->string('scanner_user_agent')->nullable(); // Browser/device info
            $table->string('scanner_device_type')->nullable(); // mobile, desktop, tablet
            $table->json('scanner_location')->nullable(); // Geolocation data if available
            /*
                Example structure:
                {
                    "country": "Indonesia",
                    "city": "Jakarta",
                    "lat": -6.2088,
                    "lng": 106.8456
                }
            */

            // Referrer Information
            $table->string('referrer_url')->nullable(); // URL where scan originated
            $table->string('source')->nullable(); // Website, mobile app, etc.

            // Response Data
            $table->json('validation_response')->nullable(); // Response data sent to scanner
            $table->integer('response_time_ms')->nullable(); // Response time in milliseconds

            // Security
            $table->boolean('is_suspicious')->default(false); // Flagged as suspicious activity
            $table->text('suspicious_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('certificate_id');
            $table->index('scanned_at');
            $table->index('validation_status');
            $table->index('scanner_ip');
            $table->index(['certificate_id', 'scanned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_qr_validation');
    }
};
