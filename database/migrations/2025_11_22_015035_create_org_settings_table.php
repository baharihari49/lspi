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
        Schema::create('org_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Setting key (e.g., assessment.max_participants)');
            $table->text('value')->nullable()->comment('Setting value (can be JSON)');
            $table->string('type')->default('string')->comment('Data type: string, number, boolean, json, date');
            $table->string('group')->nullable()->comment('Setting group (e.g., assessment, payment, certificate)');
            $table->string('label')->nullable()->comment('Human readable label');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false)->comment('Can be viewed by non-admin');
            $table->boolean('is_editable')->default(true)->comment('Can be edited via UI');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_settings');
    }
};
