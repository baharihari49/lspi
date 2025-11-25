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

            // File information
            $table->string('filename', 255);
            $table->string('original_filename', 255);
            $table->string('file_path', 500);
            $table->string('disk', 50)->default('public'); // local, public, s3, etc
            $table->string('mime_type', 100);
            $table->string('extension', 20);
            $table->unsignedBigInteger('file_size'); // in bytes

            // File categorization
            $table->string('file_type', 50)->nullable(); // document, image, video, etc
            $table->string('category', 100)->nullable(); // profile_photo, certificate, document, etc
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // width, height, duration, etc

            // Polymorphic relation - belongs to any model
            $table->string('fileable_type', 100)->nullable();
            $table->unsignedBigInteger('fileable_id')->nullable();
            $table->index(['fileable_type', 'fileable_id']);

            // Uploader information
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('uploaded_by_name', 200)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Security & Access
            $table->boolean('is_public')->default(false);
            $table->string('access_token', 64)->nullable()->unique(); // for secure download links
            $table->timestamp('expires_at')->nullable(); // for temporary files

            // File status
            $table->string('status', 50)->default('active'); // active, deleted, archived
            $table->timestamp('downloaded_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('filename');
            $table->index('mime_type');
            $table->index('file_type');
            $table->index('category');
            $table->index('uploaded_by');
            $table->index('status');
            $table->index('created_at');
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
