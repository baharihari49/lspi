<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessor_bank_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessor_id')->constrained('assessors')->cascadeOnDelete();
            
            // Bank details
            $table->string('bank_name');
            $table->string('bank_code', 10)->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number');
            $table->string('account_holder_name');
            
            // Tax information
            $table->string('npwp_number', 20)->nullable()->comment('Nomor Pokok Wajib Pajak');
            $table->string('tax_name')->nullable()->comment('Nama sesuai NPWP');
            $table->text('tax_address')->nullable();
            
            // Verification
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            // Supporting documents
            $table->foreignId('bank_statement_file_id')->nullable()->constrained('files')->nullOnDelete();
            $table->foreignId('npwp_file_id')->nullable()->constrained('files')->nullOnDelete();
            
            $table->boolean('is_primary')->default(true);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['assessor_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessor_bank_info');
    }
};
