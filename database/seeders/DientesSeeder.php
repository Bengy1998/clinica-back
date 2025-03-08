<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dientes = [
            ['codigo_fdi' => 11, 'nombre' => 'Incisivo central superior derecho'],
            ['codigo_fdi' => 12, 'nombre' => 'Incisivo lateral superior derecho'],
            ['codigo_fdi' => 13, 'nombre' => 'Canino superior derecho'],
            ['codigo_fdi' => 14, 'nombre' => 'Primer premolar superior derecho'],
            ['codigo_fdi' => 15, 'nombre' => 'Segundo premolar superior derecho'],
            ['codigo_fdi' => 16, 'nombre' => 'Primer molar superior derecho'],
            ['codigo_fdi' => 17, 'nombre' => 'Segundo molar superior derecho'],
            ['codigo_fdi' => 18, 'nombre' => 'Tercer molar superior derecho'],
            ['codigo_fdi' => 21, 'nombre' => 'Incisivo central superior izquierdo'],
            ['codigo_fdi' => 22, 'nombre' => 'Incisivo lateral superior izquierdo'],
            ['codigo_fdi' => 23, 'nombre' => 'Canino superior izquierdo'],
            ['codigo_fdi' => 24, 'nombre' => 'Primer premolar superior izquierdo'],
            ['codigo_fdi' => 25, 'nombre' => 'Segundo premolar superior izquierdo'],
            ['codigo_fdi' => 26, 'nombre' => 'Primer molar superior izquierdo'],
            ['codigo_fdi' => 27, 'nombre' => 'Segundo molar superior izquierdo'],
            ['codigo_fdi' => 28, 'nombre' => 'Tercer molar superior izquierdo'],
            ['codigo_fdi' => 31, 'nombre' => 'Incisivo central inferior izquierdo'],
            ['codigo_fdi' => 32, 'nombre' => 'Incisivo lateral inferior izquierdo'],
            ['codigo_fdi' => 33, 'nombre' => 'Canino inferior izquierdo'],
            ['codigo_fdi' => 34, 'nombre' => 'Primer premolar inferior izquierdo'],
            ['codigo_fdi' => 35, 'nombre' => 'Segundo premolar inferior izquierdo'],
            ['codigo_fdi' => 36, 'nombre' => 'Primer molar inferior izquierdo'],
            ['codigo_fdi' => 37, 'nombre' => 'Segundo molar inferior izquierdo'],
            ['codigo_fdi' => 38, 'nombre' => 'Tercer molar inferior izquierdo'],
            ['codigo_fdi' => 41, 'nombre' => 'Incisivo central inferior derecho'],
            ['codigo_fdi' => 42, 'nombre' => 'Incisivo lateral inferior derecho'],
            ['codigo_fdi' => 43, 'nombre' => 'Canino inferior derecho'],
            ['codigo_fdi' => 44, 'nombre' => 'Primer premolar inferior derecho'],
            ['codigo_fdi' => 45, 'nombre' => 'Segundo premolar inferior derecho'],
            ['codigo_fdi' => 46, 'nombre' => 'Primer molar inferior derecho'],
            ['codigo_fdi' => 47, 'nombre' => 'Segundo molar inferior derecho'],
            ['codigo_fdi' => 48, 'nombre' => 'Tercer molar inferior derecho'],
        ];

        DB::table('dientes')->upsert($dientes, ['codigo_fdi'], ['nombre']);

    }
}
