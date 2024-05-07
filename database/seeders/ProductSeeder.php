<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'First Product',
                'description' => null,
                'category' => 'First Category',
                'active' => true
            ], [
                'name' => 'Second Product',
                'description' => null,
                'category' => 'Second Category',
                'active' => false
            ]
        ];

        foreach ($products as $product) {
            $category = Category::where('name', $product['category'])->first();

            Product::updateOrCreate([
                'name' => $product['name']
            ], [
                'description' => $product['description'],
                'category_id' => $category->id,
                'active' => $product['active']
            ]);
        }
    }
}
