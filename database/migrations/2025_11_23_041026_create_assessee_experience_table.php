<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessee_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessee_id')->constrained()->cascadeOnDelete();

            // Experience Type
            $table->enum('experience_type', ['professional', 'project', 'volunteer', 'certification', 'training', 'publication', 'award', 'other'])->default('professional');

            // Basic Information
            $table->string('title');
            $table->string('organization')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();

            // Duration
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_ongoing')->default(false);

            // Details
            $table->text('responsibilities')->nullable();
            $table->text('outcomes')->nullable();
            $table->text('skills_gained')->nullable();

            // For Certifications
            $table->string('certificate_number')->nullable();
            $table->string('issuing_organization')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();

            // For Publications
            $table->string('publication_title')->nullable();
            $table->string('publisher')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('publication_url')->nullable();
            $table->string('doi')->nullable()->comment('Digital Object Identifier');

            // For Awards
            $table->string('award_name')->nullable();
            $table->string('award_issuer')->nullable();
            $table->date('award_date')->nullable();

            // Documentation
            $table->string('evidence_file')->nullable()->comment('Supporting document');
            $table->string('reference_url')->nullable();

            // Relevance
            $table->text('relevance_to_certification')->nullable();
            $table->integer('relevance_score')->nullable()->comment('1-10 scale');

            // Verification
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessee_experience');
    }
};
