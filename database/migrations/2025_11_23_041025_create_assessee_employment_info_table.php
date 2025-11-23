<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessee_employment_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessee_id')->constrained()->cascadeOnDelete();

            // Company Information
            $table->string('company_name');
            $table->string('company_industry')->nullable();
            $table->string('company_location')->nullable();
            $table->string('company_website')->nullable();

            // Position Details
            $table->string('position_title');
            $table->string('department')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship', 'freelance'])->default('full_time');
            $table->text('job_description')->nullable();

            // Duration
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);

            // Responsibilities
            $table->text('responsibilities')->nullable();
            $table->text('achievements')->nullable();
            $table->text('skills_used')->nullable();

            // Reference
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_title')->nullable();
            $table->string('supervisor_contact')->nullable();

            // Verification
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessee_employment_info');
    }
};
