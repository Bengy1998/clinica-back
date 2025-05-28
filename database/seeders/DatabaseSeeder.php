<?php

namespace Database\Seeders;

use App\Models\Aseguradora;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Cita;
use App\Models\Empresa;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\User;
use Database\Seeders\CitaEstadoSeeder;
use Database\Seeders\ClienteSeeder;
use Database\Seeders\DientesSeeder;
use Database\Seeders\EspecialidadesSeeder;
use Database\Seeders\MotivoCitaSeeder;
use Database\Seeders\PermisosSeed;
use Database\Seeders\TipoDocumentoIdentidadSeeder;
use Database\Seeders\TratamientoTiposSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            CitaEstadoSeeder::class,
            ClienteSeeder::class,
            DientesSeeder::class,
            EspecialidadesSeeder::class,
            MotivoCitaSeeder::class,
            TipoDocumentoIdentidadSeeder::class,
            DefaultDataSeeder::class,
            PermisosSeed::class,
            TratamientoTiposSeeder::class,

        ]);

        Paciente::factory()->count(20)->create();
        Empresa::factory()->count(10)->create();
        Aseguradora::factory()->count(10)->create();
        Medico::factory()->count(10)->create();
        Medicamento::factory()->count(15)->create();
        Cita::factory()->count(30)->create();
    }
}
