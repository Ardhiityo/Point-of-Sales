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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id');
            $table->string('customer');
            $table->unsignedBigInteger('payment_amount');
            $table->unsignedBigInteger('balance_returned');
            $table->enum('payment_method', ['cash', 'cashless']);
            $table->unsignedBigInteger('cost_subtotal');
            $table->unsignedBigInteger('cost_grandtotal');
            $table->unsignedBigInteger('sales_subtotal');
            $table->unsignedBigInteger('sales_grandtotal');
            $table->unsignedBigInteger('profit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
