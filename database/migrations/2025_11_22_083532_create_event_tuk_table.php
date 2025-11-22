<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tuk_id')->constrained('tuk')->cascadeOnDelete();
            $table->foreignId('event_session_id')->nullable()->constrained()->nullOnDelete(); // Optional: specific to session
            
            // Assignment details
            $table->text('notes')->nullable();
            $table->boolean('is_primary')->default(false); // Primary or backup TUK
            
            // Status
            $table->enum('status', ['assigned', 'confirmed', 'rejected', 'completed'])->default('assigned');
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['event_id', 'tuk_id', 'event_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tuk');
    }
};
