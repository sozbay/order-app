<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [

                "name" => "Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti",
                "category" => 1,
                "price" => "120.75",
                "stock" => 10
            ],
            [
                "name" => "Reko Mini Tamir Hassas Tornavida Seti 32'li",
                "category" => 2,
                "price" => "49.50",
                "stock" => 10
            ],
            [
                "name" => "Viko Karre Anahtar - Beyaz",
                "category" => 2,
                "price" => "11.28",
                "stock" => 10
            ],
            [
                "name" => "Legrand Salbei Anahtar, Alüminyum",
                "category" => 2,
                "price" => "22.80",
                "stock" => 10
            ],
            [
                "description" => "Schneider Asfora Beyaz Komütatör",
                "category" => 2,
                "price" => "12.95",
                "stock" => 10
            ],
            [
                "description" => "Anahtar",
                "category" => 1,
                "price" => "112.95",
                "stock" => 10
            ],
            [
                "description" => "Tornavida",
                "category" => 2,
                "price" => "25.25",
                "stock" => 10
            ],
            [
                "description" => "Vida",
                "category" => 2,
                "price" => "51.25",
                "stock" => 10
            ],
            [
                "description" => "Kapı Kilit",
                "category" => 1,
                "price" => "132.55",
                "stock" => 10
            ],
            [
                "description" => "Anahtarlık",
                "category" => 1,
                "price" => "232.55",
                "stock" => 10
            ],
            [
                "description" => "Matkap",
                "category" => 2,
                "price" => "332.55",
                "stock" => 10
            ],
            [
                "description" => "Balta",
                "category" => 2,
                "price" => "250",
                "stock" => 10
            ],
            [
                "description" => "Hilti",
                "category" => 2,
                "price" => "652.35",
                "stock" => 10
            ]
        ]);
    }
}
