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
        Schema::create('certificate_logs', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('certificate_id')->constrained('certificates')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // User who performed action

            // Log Entry
            $table->string('action'); // created, updated, issued, renewed, revoked, suspended, reactivated, verified, downloaded, printed
            $table->text('description'); // Human-readable description of action
            $table->json('changes')->nullable(); // Before/after data for updates
            /*
                Example structure:
                {
                    "before": {
                        "status": "active",
                        "valid_until": "2025-12-31"
                    },
                    "after": {
                        "status": "renewed",
                        "valid_until": "2028-12-31"
                    }
                }
            */

            // Context
            $table->string('ip_address')->nullable(); // IP address of user
            $table->string('user_agent')->nullable(); // Browser/device info
            $table->json('metadata')->nullable(); // Additional contextual data

            // Categorization
            $table->enum('log_level', [
                'info',
                'warning',
                'error',
                'critical'
            ])->default('info');

            $table->enum('log_category', [
                'lifecycle',      // Certificate lifecycle events
                'security',       // Security-related events
                'verification',   // Verification checks
                'access',         // Access/download events
                'system'          // System-level events
            ])->default('lifecycle');

            $table->timestamps();

            // Indexes
            $table->index('certificate_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('log_level');
            $table->index('log_category');
            $table->index('created_at');
            $table->index(['certificate_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_logs');
    }
};
