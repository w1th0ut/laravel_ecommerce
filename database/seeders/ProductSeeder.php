<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'short_description' => 'Latest iPhone with advanced features',
                'description' => 'The iPhone 15 Pro features a titanium design, A17 Pro chip, and advanced camera system.',
                'price' => 999.00,
                'sale_price' => 899.00,
                'stock_quantity' => 50,
                'sku' => 'IP15PRO001',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'is_featured' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'short_description' => 'Powerful Android smartphone',
                'description' => 'Samsung Galaxy S24 with AI features and excellent camera quality.',
                'price' => 799.00,
                'stock_quantity' => 30,
                'sku' => 'SGS24001',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'is_featured' => true,
            ],
            [
                'name' => 'Nike Air Max 270',
                'short_description' => 'Comfortable running shoes',
                'description' => 'Nike Air Max 270 provides all-day comfort with maximum Air cushioning.',
                'price' => 150.00,
                'sale_price' => 120.00,
                'stock_quantity' => 100,
                'sku' => 'NAM270001',
                'category_id' => $categories->where('name', 'Clothing')->first()->id,
            ],
            [
                'name' => 'The Psychology of Programming',
                'short_description' => 'Essential book for developers',
                'description' => 'A classic book about the human factors in computer programming.',
                'price' => 29.99,
                'stock_quantity' => 200,
                'sku' => 'BOOK001',
                'category_id' => $categories->where('name', 'Books')->first()->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}