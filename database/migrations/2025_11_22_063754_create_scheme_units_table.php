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
        Schema::create('scheme_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_version_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // Kode unit kompetensi, e.g., J.62.001.01
            $table->string('title'); // Judul unit kompetensi
            $table->text('description')->nullable();
            $table->enum('unit_type', ['core', 'optional', 'supporting'])->default('core'); // Tipe unit
            $table->integer('credit_hours')->nullable(); // Jam kredit
            $table->integer('order')->default(0); // Urutan tampilan
            $table->boolean('is_mandatory')->default(true); // Wajib atau tidak
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheme_version_id', 'order']);
            $table->index('code');
            $table->index('unit_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_units');
    }
};
