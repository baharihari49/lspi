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
        Schema::create('payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();

            // Item Details
            $table->string('item_type', 50); // event_fee, assessment_fee, certificate_fee, renewal_fee, etc
            $table->string('item_code', 50)->nullable(); // SKU or product code
            $table->string('item_name', 200);
            $table->text('description')->nullable();

            // Related Entity (optional)
            $table->string('related_type', 50)->nullable(); // event, scheme, certificate, etc
            $table->unsignedBigInteger('related_id')->nullable();

            // Pricing
            $table->decimal('unit_price', 15, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2); // unit_price * quantity
            $table->decimal('total', 15, 2); // subtotal - discount + tax

            // Tax Details
            $table->string('tax_type', 50)->nullable(); // PPN, PPh, etc
            $table->decimal('tax_percentage', 5, 2)->nullable();

            // Additional Info
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('payment_id');
            $table->index('item_type');
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_items');
    }
};
