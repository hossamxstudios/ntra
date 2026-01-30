<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imei_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->foreignId('mobile_device_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('passenger_id')->nullable()->constrained()->nullOnDelete();
            $table->string('scanned_imei', 20)->nullable();
            $table->string('phone_serial')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('scanned_imei');
            $table->index('phone_serial');
            $table->index('status');
            $table->index('checked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imei_checks');
    }
};
