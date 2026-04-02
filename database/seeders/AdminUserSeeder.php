<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@felestrino.com.br'],
            [
                'name' => 'Administrador',
                'password' => 'admin123456',
                'is_admin' => true,
            ]
        );
    }
}
