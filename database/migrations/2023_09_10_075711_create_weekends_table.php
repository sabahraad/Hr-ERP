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
        Schema::create('weekends', function (Blueprint $table) {
            $table->id('weekends_id');
            $table->boolean('Sunday')->default(0)->nullable();
            $table->boolean('Monday')->default(0)->nullable();
            $table->boolean('Tuesday')->default(0)->nullable();
            $table->boolean('Wednesday')->default(0)->nullable();
            $table->boolean('Thursday')->default(0)->nullable();
            $table->boolean('Friday')->default(0)->nullable();
            $table->boolean('Saturday')->default(0)->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekends');
    }
};
