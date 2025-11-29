<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to include pending_confirmation
        DB::statement("ALTER TABLE assessments MODIFY COLUMN status ENUM('draft', 'pending_confirmation', 'scheduled', 'in_progress', 'completed', 'under_review', 'verified', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove pending_confirmation from ENUM (revert to original)
        DB::statement("ALTER TABLE assessments MODIFY COLUMN status ENUM('draft', 'scheduled', 'in_progress', 'completed', 'under_review', 'verified', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'draft'");
    }
};
