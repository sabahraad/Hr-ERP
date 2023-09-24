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
            $table->id('attendance_id');
            $table->boolean('IN');
            $table->boolean('OUT')->nullable();
            $table->string('lateINreason')->nullable();
            $table->string('edited')->nullable();
            $table->string('editedBY')->nullable();
            $table->integer('INstatus')->default(0);
            $table->integer('OUTstatus')->default(0);
            $table->string('earlyOUTreason')->nullable();
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('id');
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
