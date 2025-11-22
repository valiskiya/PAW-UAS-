<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'code' => 'PRD001',
                'name' => 'Indomie Goreng',
                'description' => 'Mie instan rasa goreng original',
                'category' => 'Makanan',
                'large_unit' => 'Karton',
                'small_unit' => 'Bungkus',
                'conversion_factor' => 40, // 1 karton = 40 bungkus
                'stock_large' => 50,
                'stock_small' => 120,
                'min_stock' => 200,
                'purchase_price' => 2500,
                'selling_price_retail' => 3500,
                'selling_price_member' => 3300,
                'selling_price_wholesale_low' => 3000,
                'selling_price_wholesale_high' => 2800,
                'status' => 'active',
            ],
            [
                'code' => 'PRD002',
                'name' => 'Aqua 600ml',
                'description' => 'Air mineral dalam kemasan',
                'category' => 'Minuman',
                'large_unit' => 'Karton',
                'small_unit' => 'Botol',
                'conversion_factor' => 24, // 1 karton = 24 botol
                'stock_large' => 30,
                'stock_small' => 50,
                'min_stock' => 100,
                'purchase_price' => 3000,
                'selling_price_retail' => 4000,
                'selling_price_member' => 3800,
                'selling_price_wholesale_low' => 3500,
                'selling_price_wholesale_high' => 3300,
                'status' => 'active',
            ],
            [
                'code' => 'PRD003',
                'name' => 'Beras Premium 5kg',
                'description' => 'Beras kualitas premium',
                'category' => 'Sembako',
                'large_unit' => 'Karung',
                'small_unit' => 'Kg',
                'conversion_factor' => 50, // 1 karung = 50 kg
                'stock_large' => 20,
                'stock_small' => 75,
                'min_stock' => 500,
                'purchase_price' => 12000,
                'selling_price_retail' => 15000,
                'selling_price_member' => 14500,
                'selling_price_wholesale_low' => 14000,
                'selling_price_wholesale_high' => 13500,
                'status' => 'active',
            ],
            [
                'code' => 'PRD004',
                'name' => 'Minyak Goreng 2L',
                'description' => 'Minyak goreng kemasan 2 liter',
                'category' => 'Sembako',
                'large_unit' => 'Karton',
                'small_unit' => 'Botol',
                'conversion_factor' => 6, // 1 karton = 6 botol
                'stock_large' => 40,
                'stock_small' => 25,
                'min_stock' => 50,
                'purchase_price' => 28000,
                'selling_price_retail' => 35000,
                'selling_price_member' => 33000,
                'selling_price_wholesale_low' => 32000,
                'selling_price_wholesale_high' => 30000,
                'status' => 'active',
            ],
            [
                'code' => 'PRD005',
                'name' => 'Gula Pasir 1kg',
                'description' => 'Gula pasir putih',
                'category' => 'Sembako',
                'large_unit' => 'Karung',
                'small_unit' => 'Kg',
                'conversion_factor' => 50, // 1 karung = 50 kg
                'stock_large' => 15,
                'stock_small' => 80,
                'min_stock' => 300,
                'purchase_price' => 13000,
                'selling_price_retail' => 16000,
                'selling_price_member' => 15500,
                'selling_price_wholesale_low' => 15000,
                'selling_price_wholesale_high' => 14500,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}