<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessor_id')->constrained('assessors')->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained('master_document_types')->cascadeOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document_number')->nullable();
            
            // File
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            
            // Dates
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Verification workflow
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['assessor_id', 'verification_status']);
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessor_documents');
    }
};
