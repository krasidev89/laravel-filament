<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'First Category',
                'description' => null,
                'active' => true
            ], [
                'name' => 'Second Category',
                'description' => null,
                'active' => false
            ]
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate([
                'name' => $category['name']
            ], [
                'description' => $category['description'],
                'active' => $category['active']
            ]);
        }
    }
}
