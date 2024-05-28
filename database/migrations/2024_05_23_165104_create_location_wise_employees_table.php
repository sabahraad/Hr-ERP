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
        Schema::create('location_wise_employees', function (Blueprint $table) {
            $table->bigIncrements('location_wise_employees_id');
            $table->json('employee_ids');
            $table->bigInteger('office_locations_id')->unsigned();
            $table->foreign('office_locations_id')->references('office_locations_id')->on('office_locations')->onDelete('cascade');
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
        Schema::dropIfExists('location_wise_employees');
    }
};
