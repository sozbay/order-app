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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->float('unit_price');
            $table->float('total');
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
