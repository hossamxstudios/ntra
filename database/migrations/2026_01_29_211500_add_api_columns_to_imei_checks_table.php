<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imei_checks', function (Blueprint $table) {
            $table->foreignId('machine_id')->nullable()->change();
            $table->json('api_response')->nullable()->after('phone_serial');
        });

        DB::statement("ALTER TABLE imei_checks MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled', 'found', 'not_found', 'error') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('imei_checks', function (Blueprint $table) {
            $table->dropColumn('api_response');
        });

        DB::statement("ALTER TABLE imei_checks MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
