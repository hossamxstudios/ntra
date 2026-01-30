<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobiles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->decimal('estimated_price', 10, 2)->default(0);
            $table->timestamps();

            $table->index('brand');
            $table->index('model');
            $table->index(['brand', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobiles');
    }
};
