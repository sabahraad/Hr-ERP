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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('products_id');
            $table->string('product_name');
            $table->text('product_description')->nullable();
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
        Schema::dropIfExists('products');
    }
};
