<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->string('machine_token')->nullable()->unique()->after('uuid');
            $table->string('ip_address')->nullable()->after('status');
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->string('device_type')->nullable()->after('user_agent');
            $table->timestamp('last_seen_at')->nullable()->after('device_type');

            $table->index('ip_address');
            $table->index('machine_token');
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['machine_token']);
            $table->dropColumn(['machine_token', 'ip_address', 'user_agent', 'device_type', 'last_seen_at']);
        });
    }
};
