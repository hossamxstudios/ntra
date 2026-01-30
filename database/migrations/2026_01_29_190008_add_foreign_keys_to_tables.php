<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobile_devices', function (Blueprint $table) {
            $table->foreign('passenger_id')->references('id')->on('passengers')->nullOnDelete();
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->foreign('imei_check_id')->references('id')->on('imei_checks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mobile_devices', function (Blueprint $table) {
            $table->dropForeign(['passenger_id']);
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['imei_check_id']);
        });
    }
};
