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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // transfer_bank, credit_card, qris, etc
            $table->string('name', 100);
            $table->string('category', 50); // bank_transfer, e_wallet, credit_card, cash, etc
            $table->text('description')->nullable();

            // Account/Provider Info
            $table->string('bank_name', 100)->nullable(); // For bank transfer
            $table->string('account_number', 50)->nullable();
            $table->string('account_holder_name', 100)->nullable();
            $table->string('provider_name', 100)->nullable(); // For e-wallet/gateway

            // Gateway Integration
            $table->string('gateway_code', 50)->nullable(); // midtrans, xendit, etc
            $table->json('gateway_config')->nullable(); // API keys, merchant IDs, etc

            // Fees & Limits
            $table->decimal('admin_fee_percentage', 5, 2)->default(0); // percentage
            $table->decimal('admin_fee_fixed', 15, 2)->default(0); // fixed amount
            $table->decimal('min_amount', 15, 2)->nullable();
            $table->decimal('max_amount', 15, 2)->nullable();

            // Display & Status
            $table->string('icon_path', 255)->nullable();
            $table->string('logo_url', 255)->nullable();
            $table->text('instructions')->nullable(); // Payment instructions
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_manual_verification')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
