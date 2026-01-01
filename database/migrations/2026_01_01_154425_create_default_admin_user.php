<?php

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
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // CREATE ADMIN USER WITH ENCRYPTED PASSWORD
        $admin = User::firstOrCreate(
            ['email' => 'joan@beanthere.com'],
            [
                'name' => 'Admin Joan',
                'password' => Hash::make('admin123'), // SECURED & ENCRYPTED
            ]
        );
        $admin->assignRole('admin');

        // CREATE STAFF USER WITH ENCRYPTED PASSWORD
        $staff = User::firstOrCreate(
            ['email' => 'bianca@beanthere.com'],
            [
                'name' => 'Staff Bianca',
                'password' => Hash::make('staff123'), // SECURED & ENCRYPTED
            ]
        );
        $staff->assignRole('staff');
    }

    public function down(): void
    {
        // DELETE USERS ON ROLLBACK
        User::where('email', 'joan@beanthere.com')->delete();
        User::where('email', 'bianca@beanthere.com')->delete();
    }
};
