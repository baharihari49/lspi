<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_session_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Participant
            
            // Check-in/Check-out tracking
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->string('check_in_method')->nullable(); // manual, qr_code, nfc
            $table->string('check_out_method')->nullable();
            
            // Location tracking (optional)
            $table->string('check_in_location')->nullable();
            $table->string('check_out_location')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            
            // Status
            $table->enum('status', ['registered', 'checked_in', 'checked_out', 'absent', 'excused'])->default('registered');
            $table->text('notes')->nullable();
            $table->text('excuse_reason')->nullable();
            
            // Recorded by
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['event_id', 'event_session_id', 'user_id'], 'event_session_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendance');
    }
};
