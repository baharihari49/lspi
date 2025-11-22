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
        Schema::create('tuk_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tuk_id')->constrained('tuk')->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained('master_document_types')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuk_documents');
    }
};
