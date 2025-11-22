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
        Schema::create('lsp_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode LSP (e.g., LSP-PIE-001)');
            $table->string('name');
            $table->string('legal_name')->nullable()->comment('Nama resmi/hukum');
            $table->string('license_number')->unique()->comment('Nomor lisensi BNSP');
            $table->date('license_issued_date')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('accreditation_number')->nullable();
            $table->date('accreditation_expiry_date')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();

            // Branding
            $table->foreignId('logo_file_id')->nullable()->constrained('files')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();

            // Director/Head
            $table->string('director_name')->nullable();
            $table->string('director_position')->nullable();
            $table->string('director_phone')->nullable();
            $table->string('director_email')->nullable();

            // Status
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lsp_profiles');
    }
};
