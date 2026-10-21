<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empleado>
 */
class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    public function definition(): array
    {
        return [
            'nombre_completo' => fake()->name(),
            'dni' => fake()->unique()->numerify('########'),
            'cargo' => fake()->jobTitle(),
            'telefono' => fake()->numerify('9########'),
            'estado' => true,
        ];
    }
}
