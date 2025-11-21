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
        Schema::create('master_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // user, assessment, certificate, payment, apl, event, etc.
            $table->string('code'); // active, inactive, pending, approved, rejected, etc.
            $table->string('label'); // Display label
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['category', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_statuses');
    }
};
