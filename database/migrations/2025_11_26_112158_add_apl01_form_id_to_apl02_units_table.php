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
        Schema::table('apl02_units', function (Blueprint $table) {
            if (!Schema::hasColumn('apl02_units', 'apl01_form_id')) {
                $table->foreignId('apl01_form_id')->nullable()->after('event_id')->constrained('apl01_forms')->nullOnDelete();
                $table->index('apl01_form_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apl02_units', function (Blueprint $table) {
            if (Schema::hasColumn('apl02_units', 'apl01_form_id')) {
                $table->dropForeign(['apl01_form_id']);
                $table->dropIndex(['apl01_form_id']);
                $table->dropColumn('apl01_form_id');
            }
        });
    }
};
