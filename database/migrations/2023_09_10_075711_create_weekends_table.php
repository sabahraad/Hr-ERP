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
            $table->boolean('sunday')->default(0)->nullable();
            $table->boolean('monday')->default(0)->nullable();
            $table->boolean('tuesday')->default(0)->nullable();
            $table->boolean('wednesday')->default(0)->nullable();
            $table->boolean('thursday')->default(0)->nullable();
            $table->boolean('friday')->default(0)->nullable();
            $table->boolean('saturday')->default(0)->nullable();
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
