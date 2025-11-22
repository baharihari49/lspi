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
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., SKM-001
            $table->string('name'); // Nama skema
            $table->string('occupation_title')->nullable(); // Jabatan kerja
            $table->enum('qualification_level', ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'])->nullable(); // KKNI level
            $table->text('description')->nullable();
            $table->enum('scheme_type', ['occupation', 'cluster', 'qualification'])->default('occupation'); // Jenis skema
            $table->string('sector')->nullable(); // Sektor (e.g., IT, Health, etc.)
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->date('effective_date')->nullable(); // Tanggal berlaku
            $table->date('review_date')->nullable(); // Tanggal review
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'is_active']);
            $table->index('sector');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
