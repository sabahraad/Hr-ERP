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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->bigIncrements('leave_application_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('dateArray');
            $table->integer('count');
            $table->string('image')->nullable();
            $table->boolean('status')->default(0);
            $table->string('reason');
            $table->date('approvel_date')->nullable();
            $table->longText('approval_name')->nullable();
            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees');
            $table->bigInteger('leave_setting_id')->unsigned();
            $table->foreign('leave_setting_id')->references('leave_setting_id')->on('leave_settings')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
