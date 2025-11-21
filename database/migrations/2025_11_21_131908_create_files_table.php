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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('storage_disk')->default('cloudinary'); // cloudinary, local, s3
            $table->string('path'); // Full URL or path
            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->bigInteger('size')->nullable(); // in bytes
            $table->string('checksum')->nullable(); // MD5 or SHA256
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
