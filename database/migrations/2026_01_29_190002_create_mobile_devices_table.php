<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('passenger_id')->nullable();
            $table->string('device_type');
            $table->string('brand')->nullable();
            $table->string('model');
            $table->string('imei_number', 20)->nullable();
            $table->string('imei_number_2', 20)->nullable();
            $table->string('imei_number_3', 20)->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->boolean('is_activated')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('imei_number');
            $table->index('imei_number_2');
            $table->index('imei_number_3');
            $table->index('serial_number');
            $table->index('is_paid');
            $table->index('is_activated');
            $table->index('device_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_devices');
    }
};
