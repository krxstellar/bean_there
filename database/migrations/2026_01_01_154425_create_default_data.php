<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
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

    public function down(): void
    {
        // DELETE DEFAULT CATEGORIES ON ROLLBACK
        Category::where('slug', 'pastries')->delete();
        Category::where('slug', 'drinks')->delete();

        // No user deletion here — account creation was moved to a seeder.
    }
};
