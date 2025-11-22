<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessors', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('met_number')->unique()->nullable();

            // Personal Information
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('id_card_number', 16)->unique();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();

            // Contact
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email')->unique();

            // Education
            $table->string('education_level')->nullable();
            $table->string('major')->nullable();
            $table->string('institution')->nullable();

            // Professional
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();

            // Registration Info
            $table->date('registration_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('registration_status', ['active', 'inactive', 'suspended', 'expired'])->default('active');

            // Photo
            $table->foreignId('photo_file_id')->nullable()->constrained('files')->nullOnDelete();

            // Verification
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['registration_status', 'is_active']);
            $table->index('valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessors');
    }
};
