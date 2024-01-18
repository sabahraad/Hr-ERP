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
        Schema::create('mockdetails', function (Blueprint $table) {
            $table->bigIncrements('mockdetails_id');
            $table->string('emp_id');
            $table->string('emp_name');
            $table->string('email');
            $table->string('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mockdetails');
    }
};
