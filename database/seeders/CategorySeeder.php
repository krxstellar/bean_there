<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(['slug' => 'drinks'], [
            'name' => 'Drinks',
            'description' => 'Coffee, tea, and beverages',
        ]);

        Category::firstOrCreate(['slug' => 'pastries'], [
            'name' => 'Pastries',
            'description' => 'Baked goods and sweets',
        ]);
    }
}
