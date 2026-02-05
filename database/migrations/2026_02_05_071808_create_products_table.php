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
            $table->id();
            $table->string('name');
            $table->string('image_path');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('cost_price');
            $table->unsignedBigInteger('sales_price');
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete();
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
