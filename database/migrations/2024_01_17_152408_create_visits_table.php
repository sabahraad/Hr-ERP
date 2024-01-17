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
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('visits_id');
            $table->string('title');
            $table->text('desc')->nullable();
            $table->timestamp('visit_time');
            $table->string('latitude')->nullable();
            $table->string('longtitude')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'cancel', 'complete'])->default('pending');
            $table->string('cancel_reason')->nullable();
            $table->timestamp('update_time')->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
