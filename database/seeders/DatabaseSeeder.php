<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // SEED THE APPLICATION'S DATABASE
    public function run(): void
    {
        $this->call(RolesSeeder::class);
        $this->call(DefaultAccountsSeeder::class);
    }
}
