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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., EVT-2025-001
            $table->string('name'); // Nama event
            $table->foreignId('scheme_id')->nullable()->constrained()->nullOnDelete(); // Skema sertifikasi
            $table->text('description')->nullable();
            $table->enum('event_type', ['certification', 'training', 'workshop', 'other'])->default('certification');

            // Dates
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();

            // Capacity & fees
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->decimal('registration_fee', 15, 2)->nullable();

            // Status
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_active')->default(true);

            // Location
            $table->string('location')->nullable();
            $table->text('location_address')->nullable();

            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
