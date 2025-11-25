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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Notification type & channel
            $table->string('type', 100); // assessment_created, payment_received, certificate_issued, etc
            $table->string('channel', 50); // database, mail, sms, push, whatsapp
            $table->string('priority', 20)->default('normal'); // low, normal, high, urgent

            // Recipient information (polymorphic)
            $table->string('notifiable_type', 100);
            $table->unsignedBigInteger('notifiable_id');
            $table->index(['notifiable_type', 'notifiable_id']);

            // Notification content
            $table->string('title', 255);
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (action links, metadata, etc)
            $table->string('action_url', 500)->nullable();
            $table->string('action_text', 100)->nullable();

            // Related entity (optional polymorphic)
            $table->string('relatable_type', 100)->nullable();
            $table->unsignedBigInteger('relatable_id')->nullable();
            $table->index(['relatable_type', 'relatable_id']);

            // Status tracking
            $table->string('status', 50)->default('pending'); // pending, sent, failed, read
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->unsignedInteger('retry_count')->default(0);

            // Email/SMS specific fields
            $table->string('recipient_email', 200)->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->string('recipient_name', 200)->nullable();

            // Tracking
            $table->string('notification_id', 100)->nullable()->unique(); // External provider ID
            $table->json('provider_response')->nullable();
            $table->timestamp('scheduled_at')->nullable(); // For scheduled notifications

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('type');
            $table->index('channel');
            $table->index('status');
            $table->index('priority');
            $table->index('read_at');
            $table->index('sent_at');
            $table->index('scheduled_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
