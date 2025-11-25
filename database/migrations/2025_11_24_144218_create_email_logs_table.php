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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();

            // Email basic information
            $table->string('message_id', 200)->nullable()->unique(); // Provider message ID
            $table->string('subject', 500);
            $table->string('from_email', 200);
            $table->string('from_name', 200)->nullable();

            // Recipients
            $table->string('to_email', 200);
            $table->string('to_name', 200)->nullable();
            $table->string('cc_emails', 500)->nullable(); // JSON or comma-separated
            $table->string('bcc_emails', 500)->nullable(); // JSON or comma-separated
            $table->string('reply_to', 200)->nullable();

            // Email content
            $table->text('body_html')->nullable();
            $table->text('body_text')->nullable();
            $table->json('attachments')->nullable(); // Array of attachment info

            // Email type & category
            $table->string('email_type', 100)->nullable(); // welcome, verification, notification, etc
            $table->string('template_name', 100)->nullable();
            $table->json('template_data')->nullable(); // Variables used in template

            // Related entity (polymorphic)
            $table->string('emailable_type', 100)->nullable();
            $table->unsignedBigInteger('emailable_id')->nullable();
            $table->index(['emailable_type', 'emailable_id']);

            // Sender information
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sent_by_name', 200)->nullable();

            // Status tracking
            $table->string('status', 50)->default('pending'); // pending, sent, delivered, bounced, failed, opened, clicked
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            // Tracking metrics
            $table->unsignedInteger('open_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamp('last_opened_at')->nullable();
            $table->timestamp('last_clicked_at')->nullable();

            // Provider information
            $table->string('provider', 50)->default('smtp'); // smtp, sendgrid, mailgun, ses, etc
            $table->json('provider_response')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('retry_count')->default(0);

            // Additional data
            $table->json('headers')->nullable(); // Email headers
            $table->json('metadata')->nullable(); // Custom metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('to_email');
            $table->index('from_email');
            $table->index('status');
            $table->index('email_type');
            $table->index('sent_at');
            $table->index('delivered_at');
            $table->index('opened_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
