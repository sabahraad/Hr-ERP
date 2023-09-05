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
        Schema::create('i_p_s', function (Blueprint $table) {
            $table->id('ip_id');
            $table->string('ip');
            $table->string('wifiName')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->boolean('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_p_s');
    }
};
