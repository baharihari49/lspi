<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessor_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessor_id')->constrained('assessors')->cascadeOnDelete();
            
            // Experience details
            $table->string('organization_name');
            $table->string('position')->nullable();
            $table->enum('experience_type', ['assessment', 'training', 'industry', 'other'])->default('assessment');
            $table->text('description')->nullable();
            
            // Period
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            
            // Location
            $table->string('location')->nullable();
            
            // Verification
            $table->string('reference_name')->nullable();
            $table->string('reference_contact')->nullable();
            $table->foreignId('document_file_id')->nullable()->constrained('files')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['assessor_id', 'experience_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessor_experience');
    }
};
