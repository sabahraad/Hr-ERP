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
        Schema::create('shift_weekends', function (Blueprint $table) {
            $table->bigIncrements('shift_weekends_id');
            $table->boolean('Sunday')->default(0)->nullable();
            $table->boolean('Monday')->default(0)->nullable();
            $table->boolean('Tuesday')->default(0)->nullable();
            $table->boolean('Wednesday')->default(0)->nullable();
            $table->boolean('Thursday')->default(0)->nullable();
            $table->boolean('Friday')->default(0)->nullable();
            $table->boolean('Saturday')->default(0)->nullable();
            $table->bigInteger('shifts_id')->unsigned();
            $table->foreign('shifts_id')->references('shifts_id')->on('shifts')->onDelete('cascade');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_weekends');
    }
};
