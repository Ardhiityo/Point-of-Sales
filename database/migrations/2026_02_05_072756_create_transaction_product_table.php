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
        Schema::create('transaction_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('cost_subtotal');
            $table->unsignedBigInteger('cost_grandtotal');
            $table->unsignedBigInteger('sales_subtotal');
            $table->unsignedBigInteger('sales_grandtotal');
            $table->unsignedBigInteger('profit');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_product');
    }
};
