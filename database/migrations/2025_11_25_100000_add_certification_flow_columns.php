<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add auto_generated to apl02_units (after metadata)
        if (!Schema::hasColumn('apl02_units', 'auto_generated')) {
            Schema::table('apl02_units', function (Blueprint $table) {
                $table->boolean('auto_generated')->default(false)->after('metadata');
            });
        }

        // Add apl01_form_id and auto_scheduled to assessments
        if (!Schema::hasColumn('assessments', 'apl01_form_id')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->foreignId('apl01_form_id')->nullable()->after('event_id')
                    ->constrained('apl01_forms')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('assessments', 'auto_scheduled')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->boolean('auto_scheduled')->default(false)->after('metadata');
            });
        }

        // Add auto_generated to certificates
        if (!Schema::hasColumn('certificates', 'auto_generated')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->boolean('auto_generated')->default(false)->after('is_renewable');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('apl02_units', 'auto_generated')) {
            Schema::table('apl02_units', function (Blueprint $table) {
                $table->dropColumn('auto_generated');
            });
        }

        if (Schema::hasColumn('assessments', 'apl01_form_id')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->dropForeign(['apl01_form_id']);
                $table->dropColumn('apl01_form_id');
            });
        }

        if (Schema::hasColumn('assessments', 'auto_scheduled')) {
            Schema::table('assessments', function (Blueprint $table) {
                $table->dropColumn('auto_scheduled');
            });
        }

        if (Schema::hasColumn('certificates', 'auto_generated')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropColumn('auto_generated');
            });
        }
    }
};
