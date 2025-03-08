<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoIdentidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentos = [
            ['id' => 1, 'nombre' => 'Documento Nacional de Identidad', 'codigo_corto' => 'DNI', 'estado' => 1],
            ['id' => 2, 'nombre' => 'Carné de extranjería', 'codigo_corto' => 'CE', 'estado' => 1],
            ['id' => 3, 'nombre' => 'Pasaporte', 'codigo_corto' => 'PAS', 'estado' => 1],
            ['id' => 4, 'nombre' => 'Documento de Identidad Extranjero', 'codigo_corto' => 'DIE', 'estado' => 1],
            ['id' => 5, 'nombre' => 'Código Único de Identificación', 'codigo_corto' => 'CUI', 'estado' => 1],
            ['id' => 6, 'nombre' => 'Código Nacido Vivo', 'codigo_corto' => 'CNV', 'estado' => 1],
            ['id' => 7, 'nombre' => 'Sin Documento de Identidad', 'codigo_corto' => 'SDI', 'estado' => 1],
            ['id' => 8, 'nombre' => 'Registro Único de Contribuyente', 'codigo_corto' => 'RUC', 'estado' => 1],
            ['id' => 9, 'nombre' => 'Número Correlativo de Organización', 'codigo_corto' => 'NCO', 'estado' => 1],
        ];

        foreach ($documentos as $documento) {
            DB::table('tipo_documento_identidad')->updateOrInsert(
                ['id' => $documento['id']],
                $documento
            );
        }
    }
}
