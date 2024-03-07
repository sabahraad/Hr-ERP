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
        Schema::create('shift_employees', function (Blueprint $table) {
            $table->bigIncrements('shift_employees_id');
            $table->json('shift_emp_list');
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
        Schema::dropIfExists('shift_employees');
    }
};
