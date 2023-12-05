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
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('attendance_id');
            $table->boolean('IN');
            $table->boolean('OUT')->nullable();
            $table->string('lateINreason')->nullable();
            $table->string('edit_reason')->nullable();
            $table->string('editedBY')->nullable();
            $table->integer('INstatus')->default(0);
            $table->integer('OUTstatus')->default(0);
            $table->string('earlyOUTreason')->nullable();
            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->bigInteger('id')->unsigned();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
