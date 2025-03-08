<?php

namespace Database\Factories;

use App\Models\Medico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medico>
 */
class MedicoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Medico::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellido_paterno' => $this->faker->lastName,
            'apellido_materno' => $this->faker->lastName,
            'tipo_documento_identidad' => $this->faker->numberBetween(1, 3), // Ajusta según los tipos de documento disponibles
            'numero_documento_identidad' => $this->faker->unique()->numerify('########'), // Genera un número aleatorio
            'usuario_id' => null, // Lo dejamos null por el momento
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
