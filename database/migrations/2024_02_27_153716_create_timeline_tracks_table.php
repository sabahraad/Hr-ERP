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
        Schema::create('timeline_tracks', function (Blueprint $table) {
            $table->bigIncrements('timeline_tracks_id');
            $table->date('track_date');
            $table->json('location');
            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees');
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
        Schema::dropIfExists('timeline_tracks');
    }
};
