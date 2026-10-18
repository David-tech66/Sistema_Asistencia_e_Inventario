<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cliente;
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
        User::firstOrCreate(
            ['email' => 'admin@mdl.com'],
            [
                'nombre_usuario' => 'Administrador MDL',
                'password' => Hash::make('password123'),
                'rol' => 'admin',
                'estado' => true,
            ]
        );

        Cliente::firstOrCreate(
            ['documento' => '00000000'],
            ['nombre' => 'Cliente General']
        );
    }
}
