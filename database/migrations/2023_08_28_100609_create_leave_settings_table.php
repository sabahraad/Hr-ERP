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
        Schema::create('leave_settings', function (Blueprint $table) {
            $table->increments('leave_setting_id');
            $table->integer('annual_leave')->nullable()->default(0);
            $table->integer('casual_leave')->nullable()->default(0);
            $table->integer('maternity_leave')->nullable()->default(0);
            $table->integer('medical_leave')->nullable()->default(0);
            $table->integer('privilege_leave')->nullable()->default(0);
            $table->integer('probationary_leave')->nullable()->default(0);
            $table->integer('half_day_leave')->nullable()->default(0);
            $table->integer('extended_leave')->nullable()->default(0);
            $table->integer('paid_leave')->nullable()->default(0);
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_settings');
    }
};
