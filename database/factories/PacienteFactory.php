<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Paciente;
use App\Models\TipoDocumentoIdentidad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Paciente::class;

    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::inRandomOrder()->first()->id ?? Empresa::factory(),
            'nombres' => $this->faker->firstName(),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),
            'tipo_documento_identidad_id' => TipoDocumentoIdentidad::inRandomOrder()->first()->id ?? TipoDocumentoIdentidad::factory(),
            'numero_documento_identidad' => $this->faker->unique()->numerify('###########'),
            'telefono' => $this->faker->optional()->phoneNumber(),
            'email' => $this->faker->optional()->email(), // ✅ Corrección
            'fecha_nacimiento' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
