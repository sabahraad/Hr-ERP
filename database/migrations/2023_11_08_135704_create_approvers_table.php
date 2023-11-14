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
        Schema::create('approvers', function (Blueprint $table) {
            $table->bigIncrements('approvers_id');
            $table->integer('deptId');
            $table->string('deptName');
            $table->string('approver_name');
            $table->string('emp_id');
            $table->integer('priority');
            $table->bigInteger('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvers');
    }
};
