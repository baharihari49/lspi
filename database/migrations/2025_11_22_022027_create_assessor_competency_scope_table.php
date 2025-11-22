<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessor_competency_scope', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessor_id')->constrained('assessors')->cascadeOnDelete();
            
            // Scheme/competency mapping (placeholder - will be linked to scheme table later)
            $table->string('scheme_code');
            $table->string('scheme_name');
            $table->string('competency_unit_code')->nullable();
            $table->string('competency_unit_title')->nullable();
            
            // Certification
            $table->string('certificate_number')->nullable();
            $table->date('certificate_issued_date')->nullable();
            $table->date('certificate_expiry_date')->nullable();
            
            // Approval
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->text('approval_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['assessor_id', 'approval_status']);
            $table->index('certificate_expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessor_competency_scope');
    }
};
