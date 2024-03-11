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
        Schema::create('payslips', function (Blueprint $table) {
            $table->bigIncrements('payslips_id');
            $table->integer('salary');
            $table->enum('adjustment_type',['addition','deduction']);
            $table->integer('adjusted_amount')->nullable();
            $table->string('adjustment_reason')->nullable();
            $table->integer('after_adjustment_salary')->nullable();
            $table->enum('status', ['paid', 'pending', 'cancelled'])->default('pending');
            $table->date('month');
            $table->integer('year');
            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('payslips');
    }
};
