<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id')->unique();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->foreignId('imei_check_id')->nullable()->constrained('imei_checks')->nullOnDelete();
            $table->foreignId('mobile_device_id')->nullable()->constrained('mobile_devices')->nullOnDelete();
            $table->foreignId('passenger_id')->nullable()->constrained('passengers')->nullOnDelete();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('EGP');
            $table->enum('payment_method', ['pos', 'cash', 'card', 'other'])->default('pos');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('pos_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('transaction_id');
            $table->index('status');
            $table->index('paid_at');
            $table->index('pos_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
