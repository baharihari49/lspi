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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();

            // Action information
            $table->string('event', 100); // created, updated, deleted, viewed, login, logout, etc
            $table->string('action', 255); // Description of action performed
            $table->string('module', 100)->nullable(); // assessments, payments, certificates, etc
            $table->string('severity', 20)->default('info'); // info, warning, error, critical

            // Auditable entity (polymorphic)
            $table->string('auditable_type', 100)->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->index(['auditable_type', 'auditable_id']);

            // User information
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name', 200)->nullable();
            $table->string('user_email', 200)->nullable();
            $table->string('user_role', 100)->nullable();

            // Changes made
            $table->json('old_values')->nullable(); // Previous data
            $table->json('new_values')->nullable(); // New data
            $table->json('changes')->nullable(); // Only changed fields
            $table->json('metadata')->nullable(); // Additional context data

            // Request information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->string('request_url', 500)->nullable();
            $table->json('request_data')->nullable(); // Request payload
            $table->string('session_id', 100)->nullable();

            // Response information
            $table->integer('response_code')->nullable(); // HTTP status code
            $table->string('response_status', 50)->nullable(); // success, failed, error
            $table->text('error_message')->nullable();

            // Additional tracking
            $table->json('tags')->nullable(); // For categorization/filtering
            $table->text('description')->nullable(); // Human-readable description
            $table->timestamp('performed_at')->nullable();
            $table->string('batch_id', 100)->nullable(); // For grouping related actions

            $table->timestamps();

            // Indexes
            $table->index('event');
            $table->index('module');
            $table->index('severity');
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('performed_at');
            $table->index('created_at');
            $table->index('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
