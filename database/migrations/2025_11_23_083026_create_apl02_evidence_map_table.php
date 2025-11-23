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
        Schema::create('apl02_evidence_map', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('apl02_evidence_id')->constrained('apl02_evidence')->cascadeOnDelete();
            $table->foreignId('scheme_element_id')->constrained()->cascadeOnDelete();

            // Mapping details
            $table->enum('coverage_level', [
                'full',         // Fully covers the element
                'partial',      // Partially covers the element
                'supplementary' // Provides supporting evidence
            ])->default('full');

            $table->decimal('coverage_percentage', 5, 2)->nullable(); // 0-100
            $table->text('mapping_notes')->nullable(); // How this evidence relates to the element

            // Assessment by assessor
            $table->enum('assessor_evaluation', [
                'pending',
                'accepted',
                'rejected',
                'requires_more_evidence'
            ])->default('pending');

            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('evaluated_at')->nullable();
            $table->text('evaluation_notes')->nullable();

            // Scoring
            $table->decimal('relevance_score', 5, 2)->nullable(); // How relevant is this evidence (0-100)
            $table->decimal('quality_score', 5, 2)->nullable();    // Quality of evidence (0-100)
            $table->decimal('currency_score', 5, 2)->nullable();   // How current/recent (0-100)
            $table->decimal('authenticity_score', 5, 2)->nullable(); // How authentic (0-100)

            // Status & Metadata
            $table->boolean('is_primary')->default(false); // Is this the main evidence for this element?
            $table->unsignedInteger('display_order')->default(0);
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['apl02_evidence_id', 'scheme_element_id']);
            $table->index('coverage_level');
            $table->index('assessor_evaluation');
            $table->index('display_order');

            // Ensure unique mapping (one evidence can map to same element only once)
            $table->unique(['apl02_evidence_id', 'scheme_element_id'], 'evidence_element_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_evidence_map');
    }
};
