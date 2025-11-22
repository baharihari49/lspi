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
        Schema::create('scheme_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->nullable()->constrained('master_document_types')->nullOnDelete();
            $table->string('title'); // Judul dokumen
            $table->text('description')->nullable();
            $table->foreignId('file_id')->nullable()->constrained()->nullOnDelete(); // Reference to files table
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheme_version_id', 'document_type_id']);
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_documents');
    }
};
