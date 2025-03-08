<?php

namespace Database\Factories;

use App\Models\Aseguradora;
use App\Models\Empresa;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cita>
 */
class CitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id'      => \App\Models\Empresa::inRandomOrder()->first()->id ?? 1,
            'paciente_id'     => \App\Models\Paciente::inRandomOrder()->first()->id ?? 1,
            'aseguradora_id'  => \App\Models\Aseguradora::inRandomOrder()->first()->id ?? null,
            'medico_id'       => \App\Models\Medico::inRandomOrder()->first()->id ?? 1,
            'especialidad_id' => \App\Models\Especialidad::inRandomOrder()->first()->id ?? null,
            'fecha'           => $this->faker->date(),
            'hora'            => $this->faker->time(),
            'estado_id'       => \App\Models\CitaEstado::inRandomOrder()->first()->id ?? 1,
            'motivo_cita_id'  => \App\Models\MotivoCita::inRandomOrder()->first()->id ?? null,
            'created_at'      => now(),
        ];
    }
}
