<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // SEED THE APPLICATION'S DATABASE
    public function run(): void
    {
        // CREATE ROLES
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'customer']);

        // SEED BASE DATA
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // SEED ROLE-BASED DEMO USERS
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin', 'password' => 'admin123'],
            ['name' => 'Customer User', 'email' => 'customer@example.com', 'role' => 'customer', 'password' => 'customer123'],
            ['name' => 'Staff User', 'email' => 'staff@example.com', 'role' => 'staff', 'password' => 'staff123'],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );
            $user->syncRoles([$userData['role']]);
        }
    }
}
