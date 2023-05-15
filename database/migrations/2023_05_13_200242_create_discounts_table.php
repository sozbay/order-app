<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('min_order_amount')->nullable();
            $table->integer('free_item_count')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
