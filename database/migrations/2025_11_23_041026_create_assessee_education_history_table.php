<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessee_education_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessee_id')->constrained()->cascadeOnDelete();

            // Institution Information
            $table->string('institution_name');
            $table->string('institution_location')->nullable();
            $table->string('institution_type')->nullable()->comment('University, College, School, etc.');

            // Education Details
            $table->enum('education_level', ['sd', 'smp', 'sma', 'diploma', 's1', 's2', 's3', 'other'])->comment('Elementary to Doctorate');
            $table->string('degree_name')->nullable()->comment('e.g., Bachelor of Science');
            $table->string('major')->nullable()->comment('Field of study');
            $table->string('minor')->nullable();

            // Duration
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);

            // Achievement
            $table->string('gpa')->nullable()->comment('Grade Point Average');
            $table->string('gpa_scale')->default('4.0')->comment('e.g., 4.0, 5.0, 100');
            $table->string('honors')->nullable()->comment('Cum Laude, Dean\'s List, etc.');
            $table->text('achievements')->nullable();

            // Certificate
            $table->string('certificate_number')->nullable();
            $table->date('graduation_date')->nullable();

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
        Schema::dropIfExists('assessee_education_history');
    }
};
