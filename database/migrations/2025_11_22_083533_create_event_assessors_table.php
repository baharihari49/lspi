<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_assessors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_session_id')->nullable()->constrained()->nullOnDelete();
            
            // Assignment details
            $table->enum('role', ['lead', 'member', 'observer'])->default('member');
            $table->text('notes')->nullable();
            
            // Confirmation workflow
            $table->enum('status', ['invited', 'confirmed', 'rejected', 'completed'])->default('invited');
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Honorarium
            $table->decimal('honorarium_amount', 15, 2)->nullable();
            $table->enum('payment_status', ['pending', 'processing', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['event_id', 'assessor_id', 'event_session_id'], 'event_assessor_session_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_assessors');
    }
};
