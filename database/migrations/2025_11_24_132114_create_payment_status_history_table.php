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
        Schema::create('payment_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();

            // Status Change
            $table->string('from_status', 50)->nullable(); // Previous status
            $table->string('to_status', 50); // New status
            $table->string('change_reason', 100)->nullable(); // manual_update, gateway_callback, admin_action, etc

            // Who made the change
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('changed_by_name', 100)->nullable(); // Store name for system/gateway changes

            // Additional Details
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Gateway response, verification details, etc

            // IP & User Agent (for audit)
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamp('changed_at'); // When the status changed

            $table->index('payment_id');
            $table->index('to_status');
            $table->index('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_status_history');
    }
};
