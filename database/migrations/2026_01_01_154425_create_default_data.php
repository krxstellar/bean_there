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
        // CREATE ROLES IF NOT EXISTS
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Store Manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // CREATE ADMIN USER 
        $admin = User::firstOrCreate(
            ['email' => 'joan@beanthere.com'],
            [
                'name' => 'Admin Joan',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->assignRole('admin');

        // CREATE STAFF USER 
        $staff = User::firstOrCreate(
            ['email' => 'bianca@beanthere.com'],
            [
                'name' => 'Staff Bianca',
                'password' => Hash::make('staff123'),
            ]
        );
        $staff->assignRole('Store Manager');

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

        // DELETE USERS ON ROLLBACK
        User::where('email', 'joan@beanthere.com')->delete();
        User::where('email', 'bianca@beanthere.com')->delete();
    }
};
