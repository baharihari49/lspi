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
        Schema::table('apl01_forms', function (Blueprint $table) {
            // tuk_id already exists, just add event_session_id
            $table->foreignId('event_session_id')->nullable()->after('tuk_id')->constrained('event_sessions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apl01_forms', function (Blueprint $table) {
            $table->dropForeign(['event_session_id']);
            $table->dropColumn(['event_session_id']);
        });
    }
};
