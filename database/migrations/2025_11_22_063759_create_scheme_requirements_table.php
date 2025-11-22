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
        Schema::create('scheme_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_version_id')->constrained()->cascadeOnDelete();
            $table->enum('requirement_type', ['education', 'experience', 'certification', 'training', 'physical', 'other'])->default('education');
            $table->string('title'); // Judul persyaratan
            $table->text('description'); // Deskripsi detail
            $table->boolean('is_mandatory')->default(true); // Wajib atau opsional
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheme_version_id', 'requirement_type']);
            $table->index(['scheme_version_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_requirements');
    }
};
