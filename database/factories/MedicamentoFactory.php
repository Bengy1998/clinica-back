<?php

namespace Database\Factories;

use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicamento>
 */
class MedicamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Medicamento::class;
    public function definition(): array
    {
        return [
            'nombre'             => $this->faker->word(),
            'descripcion'        => $this->faker->sentence(),
            'presentacion'       => $this->faker->randomElement(['Tabletas', 'Cápsulas', 'Jarabe', 'Inyección']),
            'contraindicaciones' => $this->faker->paragraph(),
            'created_at'         => now(),
        ];
    }
}
