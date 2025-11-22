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
        Schema::create('scheme_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_unit_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable(); // Kode elemen, e.g., 1, 2, 3
            $table->string('title'); // Judul elemen kompetensi
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheme_unit_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_elements');
    }
};
