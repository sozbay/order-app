<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('discounts')->insert([
            [
                "reason" => "10_PERCENT_OVER_1000",
                "description" => "Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.",
                "category_id" => null,
                "product_id" => null,
                "min_order_amount" => 1000,
                "free_item_count" => null,
                "quantity" => 0,
                "discount_percent" => 10,
                "discount_amount" => null

            ],
            [
                "reason" => "BUY_5_GET_1",
                "description" => "2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.",
                "category_id" => 2,
                "product_id" => null,
                "min_order_amount" => null,
                "free_item_count" => 1,
                "quantity" => 6,
                "discount_percent" => null,
                "discount_amount" => null
            ],
            [
                "reason" => "20_PERCENT_OVER_BY_CATEGORY",
                "description" => "1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.",
                "category_id" => 1,
                "product_id" => null,
                "min_order_amount" => null,
                "free_item_count" => null,
                "quantity" => 0,
                "discount_percent" => 20,
                "discount_amount" => null
            ]
        ]);
    }
}
