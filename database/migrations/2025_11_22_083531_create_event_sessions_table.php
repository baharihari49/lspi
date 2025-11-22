<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('session_code'); // e.g., S1, S2, Morning, Afternoon
            $table->string('name'); // Nama sesi
            $table->text('description')->nullable();
            
            // Schedule
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            
            // Capacity
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            
            // Room/Location
            $table->string('room')->nullable();
            $table->text('notes')->nullable();
            
            // Status
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->boolean('is_active')->default(true);
            
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['event_id', 'session_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};
