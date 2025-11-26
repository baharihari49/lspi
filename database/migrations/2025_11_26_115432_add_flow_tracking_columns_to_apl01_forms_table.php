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
            // Add flow tracking columns for certification flow
            if (!Schema::hasColumn('apl01_forms', 'flow_status')) {
                $table->string('flow_status')->nullable()->after('rejection_reason')
                    ->comment('Certification flow status: pending, apl02_generated, apl02_completed, assessment_scheduled, assessment_completed, certificate_issued');
            }

            if (!Schema::hasColumn('apl01_forms', 'apl02_generated_at')) {
                $table->timestamp('apl02_generated_at')->nullable()->after('flow_status')
                    ->comment('When APL-02 units were auto-generated');
            }

            if (!Schema::hasColumn('apl01_forms', 'assessment_scheduled_at')) {
                $table->timestamp('assessment_scheduled_at')->nullable()->after('apl02_generated_at')
                    ->comment('When assessment was scheduled');
            }

            if (!Schema::hasColumn('apl01_forms', 'certificate_issued_at')) {
                $table->timestamp('certificate_issued_at')->nullable()->after('assessment_scheduled_at')
                    ->comment('When certificate was issued');
            }

            // Add index for flow_status
            if (!Schema::hasColumn('apl01_forms', 'flow_status')) {
                $table->index('flow_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apl01_forms', function (Blueprint $table) {
            if (Schema::hasColumn('apl01_forms', 'flow_status')) {
                $table->dropIndex(['flow_status']);
                $table->dropColumn('flow_status');
            }

            if (Schema::hasColumn('apl01_forms', 'apl02_generated_at')) {
                $table->dropColumn('apl02_generated_at');
            }

            if (Schema::hasColumn('apl01_forms', 'assessment_scheduled_at')) {
                $table->dropColumn('assessment_scheduled_at');
            }

            if (Schema::hasColumn('apl01_forms', 'certificate_issued_at')) {
                $table->dropColumn('certificate_issued_at');
            }
        });
    }
};
