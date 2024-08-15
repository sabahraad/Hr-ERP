<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('checkIN_latitude', 10, 8)->nullable()->after('earlyOUTreason');
            $table->decimal('checkIN_longitude', 11, 8)->nullable()->after('checkIN_latitude');
            $table->decimal('checkOUT_latitude', 10, 8)->nullable()->after('checkIN_longitude');
            $table->decimal('checkOUT_longitude', 11, 8)->nullable()->after('checkOUT_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('checkin_latitude');
            $table->dropColumn('checkin_longitude');
            $table->dropColumn('checkout_latitude');
            $table->dropColumn('checkout_longitude');
        });
    }
};
