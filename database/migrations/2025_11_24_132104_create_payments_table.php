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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique(); // INV-2024-0001
            $table->string('invoice_number', 50)->unique()->nullable(); // External invoice reference

            // Payer Information
            $table->foreignId('payer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('payer_type', 50); // assessee, organization, etc
            $table->string('payer_name', 100);
            $table->string('payer_email', 100)->nullable();
            $table->string('payer_phone', 20)->nullable();

            // Related Entities (Polymorphic-like but using specific columns)
            $table->string('payable_type', 50); // event, assessment, certificate_renewal, etc
            $table->unsignedBigInteger('payable_id'); // ID of the related entity
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('assessment_id')->nullable()->constrained('assessments')->nullOnDelete();

            // Payment Method
            $table->foreignId('payment_method_id')->constrained('payment_methods')->restrictOnDelete();

            // Amount Details
            $table->decimal('subtotal', 15, 2); // Before fees/discounts
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2); // Final amount to pay
            $table->decimal('paid_amount', 15, 2)->default(0); // Amount actually paid

            // Discount Info
            $table->string('discount_code', 50)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();

            // Status & Dates
            $table->string('status', 50)->default('pending'); // pending, paid, partial, failed, cancelled, refunded
            $table->date('invoice_date');
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // Payment Gateway Info
            $table->string('transaction_id', 100)->nullable(); // Gateway transaction ID
            $table->string('payment_gateway', 50)->nullable(); // midtrans, xendit, etc
            $table->json('gateway_response')->nullable(); // Full gateway response
            $table->string('payment_channel', 50)->nullable(); // Bank name, e-wallet type, etc

            // Verification & Proof
            $table->string('payment_proof_path', 255)->nullable(); // Upload proof for manual transfer
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            // Additional Info
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->json('metadata')->nullable(); // Additional flexible data

            $table->timestamps();
            $table->softDeletes();

            $table->index(['payable_type', 'payable_id']);
            $table->index('payer_id');
            $table->index('status');
            $table->index('invoice_date');
            $table->index('due_date');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
