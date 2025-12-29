<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $drinks = Category::where('slug', 'drinks')->first();
        $pastries = Category::where('slug', 'pastries')->first();

        if ($drinks) {
            Product::firstOrCreate(['slug' => 'espresso'], [
                'category_id' => $drinks->id,
                'name' => 'Espresso',
                'description' => 'Rich and bold shot of espresso',
                'price' => 90.00,
                'image_url' => null,
                'is_active' => true,
            ]);

            Product::firstOrCreate(['slug' => 'latte'], [
                'category_id' => $drinks->id,
                'name' => 'Caffè Latte',
                'description' => 'Smooth milk coffee',
                'price' => 140.00,
                'image_url' => null,
                'is_active' => true,
            ]);
        }

        if ($pastries) {
            Product::firstOrCreate(['slug' => 'croissant'], [
                'category_id' => $pastries->id,
                'name' => 'Butter Croissant',
                'description' => 'Flaky, buttery pastry',
                'price' => 85.00,
                'image_url' => null,
                'is_active' => true,
            ]);

            Product::firstOrCreate(['slug' => 'brownie'], [
                'category_id' => $pastries->id,
                'name' => 'Chocolate Brownie',
                'description' => 'Dense and fudgy chocolate treat',
                'price' => 75.00,
                'image_url' => null,
                'is_active' => true,
            ]);
        }
    }
}
