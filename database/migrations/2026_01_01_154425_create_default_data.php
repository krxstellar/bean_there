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
        Category::where('slug', 'pastries')->delete();
        Category::where('slug', 'drinks')->delete();
    }
};
