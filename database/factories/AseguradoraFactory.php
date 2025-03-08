<?php

namespace Database\Factories;

use App\Models\Aseguradora;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aseguradora>
 */
class AseguradoraFactory extends Factory
{
    protected $model = Aseguradora::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company,
            'ruc' => $this->faker->unique()->numerify('###########'),
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'empresa_id' => \App\Models\Empresa::exists() ? \App\Models\Empresa::inRandomOrder()->first()->id : \App\Models\Empresa::factory()->create()->id,

            'created_at' => now(),
        ];
    }
}
