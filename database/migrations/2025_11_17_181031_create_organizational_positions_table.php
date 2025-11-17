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
        Schema::create('organizational_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama orang
            $table->string('position'); // Jabatan
            $table->string('level'); // Ketua, Wakil Ketua, Sekretaris, dll
            $table->foreignId('parent_id')->nullable()->constrained('organizational_positions')->onDelete('cascade');
            $table->integer('order')->default(0); // Urutan tampilan
            $table->string('photo')->nullable(); // Path foto
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_positions');
    }
};
