<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imei_check_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('document_number')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('mrz1')->nullable();
            $table->string('mrz2')->nullable();
            $table->string('mrz3')->nullable();
            $table->string('national_id', 20)->nullable();
            $table->string('passport_no', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('first_name');
            $table->index('last_name');
            $table->index('national_id');
            $table->index('passport_no');
            $table->index('nationality');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
