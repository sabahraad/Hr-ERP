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
        Schema::create('leave_approves', function (Blueprint $table) {
            $table->bigIncrements('leave_approves_id');
            $table->bigInteger('dept_id');
            $table->bigInteger('leave_application_id');
            $table->bigInteger('approver_emp_id');
            $table->string('approver_name');
            $table->integer('status');
            $table->integer('priority');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approves');
    }
};
