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
        Schema::create('scheme_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_element_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable(); // Kode KUK, e.g., 1.1, 1.2, 2.1
            $table->text('description'); // Deskripsi kriteria unjuk kerja
            $table->text('evidence_guide')->nullable(); // Panduan bukti
            $table->enum('assessment_method', ['written', 'practical', 'oral', 'portfolio', 'observation'])->nullable();
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheme_element_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_criteria');
    }
};
