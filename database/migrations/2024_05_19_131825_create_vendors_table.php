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
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('vendors_id');
            $table->string('vendor_name');
            $table->string('agreement_attachment')->nullable();
            $table->bigInteger('requisition_categories_id')->unsigned();
            $table->foreign('requisition_categories_id')->references('requisition_categories_id')->on('requisition_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
