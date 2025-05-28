<?php

namespace Database\Factories;

use App\Models\Especialidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class EspecialidadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word, // Genera un nombre único
            'estado' => 1, // Estado predeterminado
        ];
    }

    /**
     * Inserta todas las especialidades de la lista si no existen.
     */
    public static function insertAll(): void
    {

    }
}