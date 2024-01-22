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
        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('meetings_id');
            $table->enum('type', ['meeting', 'appointment']);
            $table->string('guest_company_name')->nullable();
            $table->dateTime('meeting_datetime');
            $table->enum('status', ['complete', 'cancel','pending'])->default('pending');
            $table->bigInteger('creator_id');
            $table->bigInteger('attendee_id')->nullable();
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
        Schema::dropIfExists('meetings');
    }
};
