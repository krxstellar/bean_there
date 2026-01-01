<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // CREATE DEFAULT CATEGORIES
        Category::firstOrCreate(
            ['slug' => 'pastries'],
            ['name' => 'Pastries']
        );

        Category::firstOrCreate(
            ['slug' => 'drinks'],
            ['name' => 'Drinks']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DELETE DEFAULT CATEGORIES ON ROLLBACK
        Category::where('slug', 'pastries')->delete();
        Category::where('slug', 'drinks')->delete();
    }
};
