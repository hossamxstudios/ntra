<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('area')->nullable();
            $table->string('place')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('area');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
