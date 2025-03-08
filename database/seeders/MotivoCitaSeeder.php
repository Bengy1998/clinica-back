<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MotivoCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('motivo_cita')->upsert([
            [
                'descripcion' => 'Consulta General',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Revisión',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'descripcion' => 'Emergencia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['descripcion'], ['updated_at']);
    }
}
