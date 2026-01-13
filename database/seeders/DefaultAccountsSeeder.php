<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        if ($adminEmail) {
            $admin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => env('ADMIN_NAME', 'Administrator'),
                    'password' => Hash::make(env('ADMIN_PASSWORD', Str::random(32))),
                ]
            );
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('admin');
            }
            $this->command->info("Default admin processed: {$adminEmail}");
        } else {
            $this->command->info('ADMIN_EMAIL not set; skipping admin creation.');
        }
    }
}
