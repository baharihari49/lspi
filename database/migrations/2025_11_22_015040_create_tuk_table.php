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
        Schema::create('tuk', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode TUK unik');
            $table->string('name');
            $table->enum('type', ['permanent', 'temporary', 'mobile'])->default('permanent');
            $table->text('description')->nullable();

            // Location
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('pic_name')->nullable()->comment('Person in charge');
            $table->string('pic_phone')->nullable();

            // Capacity
            $table->integer('capacity')->default(0)->comment('Max participants');
            $table->integer('room_count')->default(0);
            $table->decimal('area_size', 10, 2)->nullable()->comment('Area in m²');

            // Status
            $table->foreignId('status_id')->nullable()->constrained('master_statuses')->nullOnDelete();
            $table->boolean('is_active')->default(true);

            // Management
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuk');
    }
};
