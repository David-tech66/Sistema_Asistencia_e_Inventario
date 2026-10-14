<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nombre_usuario' => 'Administrador MDL',
            'email' => 'admin@mdl.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
            'estado' => true,
        ]);
    }
}
