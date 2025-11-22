<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_session_id')->nullable()->constrained()->nullOnDelete();
            
            // Material info
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('material_type', ['document', 'presentation', 'video', 'assessment', 'other'])->default('document');
            
            // File info
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            
            // Access control
            $table->boolean('is_public')->default(false);
            $table->boolean('is_downloadable')->default(true);
            
            // Distribution
            $table->timestamp('published_at')->nullable();
            $table->integer('download_count')->default(0);
            
            $table->integer('order')->default(0);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_materials');
    }
};
