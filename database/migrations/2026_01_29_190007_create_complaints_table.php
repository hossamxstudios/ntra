<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('national_id', 20)->nullable();
            $table->string('reason');
            $table->text('message');
            $table->string('other_reason')->nullable();
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('phone');
            $table->index('national_id');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
