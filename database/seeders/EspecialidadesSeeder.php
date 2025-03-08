<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            ['nombre' => 'Cardiología', 'estado' => 1],
            ['nombre' => 'Dermatología', 'estado' => 1],
            ['nombre' => 'Neurología', 'estado' => 1],
            ['nombre' => 'Oftalmología', 'estado' => 1],
            ['nombre' => 'Pediatría', 'estado' => 1],
            ['nombre' => 'Gastroenterología', 'estado' => 1],
            ['nombre' => 'Urología', 'estado' => 1],
            ['nombre' => 'Ginecología', 'estado' => 1],
            ['nombre' => 'Oncología', 'estado' => 1],
            ['nombre' => 'Endocrinología', 'estado' => 1],
            ['nombre' => 'Otorrinolaringología', 'estado' => 1],
            ['nombre' => 'Reumatología', 'estado' => 1],
            ['nombre' => 'Traumatología', 'estado' => 1],
            ['nombre' => 'Nefrología', 'estado' => 1],
            ['nombre' => 'Psiquiatría', 'estado' => 1],
            ['nombre' => 'Cirugía General', 'estado' => 1],
            ['nombre' => 'Medicina Interna', 'estado' => 1],
            ['nombre' => 'Medicina Familiar', 'estado' => 1],
            ['nombre' => 'Neumología', 'estado' => 1],
            ['nombre' => 'Infectología', 'estado' => 1],
            ['nombre' => 'Anestesiología', 'estado' => 1],
            ['nombre' => 'Cirugía Plástica', 'estado' => 1],
            ['nombre' => 'Cirugía Vascular', 'estado' => 1],
            ['nombre' => 'Medicina Física y Rehabilitación', 'estado' => 1],
            ['nombre' => 'Geriatría', 'estado' => 1],
            ['nombre' => 'Radiología', 'estado' => 1],
            ['nombre' => 'Patología', 'estado' => 1],
            ['nombre' => 'Hematología', 'estado' => 1],
            ['nombre' => 'Toxicología', 'estado' => 1],
            ['nombre' => 'Medicina del Deporte', 'estado' => 1],
            ['nombre' => 'Medicina del Trabajo', 'estado' => 1],
            ['nombre' => 'Genética Médica', 'estado' => 1],
            ['nombre' => 'Alergología', 'estado' => 1],
            ['nombre' => 'Medicina Nuclear', 'estado' => 1],
        ];

        DB::table('especialidades')->upsert($especialidades, ['nombre'], ['estado']);
    }
}
