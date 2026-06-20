<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            AdminUserSeeder::class,
            InitialContentSeeder::class,
        ]);
=======
        $this->call(CmsSeeder::class);
>>>>>>> dev2
    }
}
