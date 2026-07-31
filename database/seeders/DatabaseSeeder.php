<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KantorSeeder::class,
            JabatanSeeder::class,
            RoleSeeder::class,
            InitialUserSeeder::class,
        ]);
    }
}
