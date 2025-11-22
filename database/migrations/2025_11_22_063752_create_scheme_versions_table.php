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
        Schema::create('scheme_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained()->cascadeOnDelete();
            $table->string('version'); // e.g., 1.0, 2.0, 2.1
            $table->text('changes_summary')->nullable(); // Ringkasan perubahan
            $table->enum('status', ['draft', 'active', 'archived', 'deprecated'])->default('draft');
            $table->boolean('is_current')->default(false); // Versi yang sedang aktif
            $table->date('effective_date')->nullable(); // Tanggal berlaku
            $table->date('expiry_date')->nullable(); // Tanggal kadaluarsa
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['scheme_id', 'version']);
            $table->index(['scheme_id', 'is_current']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_versions');
    }
};
