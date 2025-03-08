<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TratamientoTiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tratamientos = [
            ['nombre' => 'Limpieza dental', 'descripcion' => 'Eliminación de placa y sarro para mantener la salud bucal.'],
            ['nombre' => 'Extracción dental', 'descripcion' => 'Remoción de un diente debido a caries, fracturas o apiñamiento.'],
            ['nombre' => 'Ortodoncia', 'descripcion' => 'Corrección de la alineación de los dientes mediante brackets o alineadores.'],
            ['nombre' => 'Endodoncia', 'descripcion' => 'Tratamiento de conductos para eliminar la infección y preservar el diente.'],
            ['nombre' => 'Obturación', 'descripcion' => 'Restauración de un diente afectado por caries con resina o amalgama.'],
            ['nombre' => 'Implante dental', 'descripcion' => 'Colocación de una raíz artificial para sustituir un diente perdido.'],
            ['nombre' => 'Blanqueamiento dental', 'descripcion' => 'Tratamiento estético para aclarar el color de los dientes.'],
            ['nombre' => 'Prótesis fija', 'descripcion' => 'Colocación de coronas o puentes para sustituir dientes ausentes.'],
            ['nombre' => 'Prótesis removible', 'descripcion' => 'Colocación de una prótesis dental que se puede retirar.'],
            ['nombre' => 'Sellantes dentales', 'descripcion' => 'Aplicación de un recubrimiento protector sobre los dientes para prevenir caries.'],
        ];

        DB::table('tratamiento_tipos')->upsert(
            array_map(fn ($t) => [
                'nombre' => $t['nombre'],
                'descripcion' => $t['descripcion'],
                'updated_at' => now()
            ], $tratamientos),
            ['nombre'], // Clave única para evitar duplicados
            ['descripcion', 'updated_at'] // Columnas a actualizar si ya existe
        );
    }
}
