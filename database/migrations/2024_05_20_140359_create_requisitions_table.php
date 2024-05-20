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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('requisitions_id');
            $table->bigInteger('requisition_categories_id')->unsigned();
            $table->bigInteger('products_id')->unsigned();
            $table->string('product_name');
            $table->integer('quantity')->default(0);
            $table->string('reason')->nullable();
            $table->enum('status',['pending','rejected','approved'])->default('pending');
            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('requisitions');
    }
};
