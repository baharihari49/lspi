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
        Schema::create('tuk_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tuk_id')->constrained('tuk')->cascadeOnDelete();
            $table->string('name');
            $table->enum('category', ['equipment', 'furniture', 'technology', 'safety', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuk_facilities');
    }
};
