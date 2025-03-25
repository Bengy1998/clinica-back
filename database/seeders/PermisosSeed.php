<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permiso;
use App\Models\Rol;
use App\Models\Role;

class PermisosSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {/*
        // Crear permisos para el módulo de empresa
        $empresa = Modulo::create(['nombre' => 'Empresa']);
        $empresa_p = [
            ['nombre' => 'Ver Empresa', 'slug' => 'ver-empresa', 'descripcion' => 'Permiso para ver empresas', 'modulo_id' => $empresa->id],
            ['nombre' => 'Crear Empresa', 'slug' => 'crear-empresa', 'descripcion' => 'Permiso para crear empresas', 'modulo_id' => $empresa->id],
            ['nombre' => 'Editar Empresa', 'slug' => 'editar-empresa', 'descripcion' => 'Permiso para editar empresas', 'modulo_id' => $empresa->id],
            ['nombre' => 'Eliminar Empresa', 'slug' => 'eliminar-empresa', 'descripcion' => 'Permiso para eliminar empresas', 'modulo_id' => $empresa->id],
        ];

        foreach ($empresa_p as $permiso) {
            Permiso::create($permiso);
        }

        // Crear permisos para el módulo de pacientes
        $pacientes = Modulo::create(['nombre' => 'Pacientes']);
        $pacientes_p = [
            ['nombre' => 'Ver Pacientes', 'slug' => 'ver-pacientes', 'descripcion' => 'Permiso para ver pacientes', 'modulo_id' => $pacientes->id],
            ['nombre' => 'Crear Pacientes', 'slug' => 'crear-pacientes', 'descripcion' => 'Permiso para crear pacientes', 'modulo_id' => $pacientes->id],
            ['nombre' => 'Editar Pacientes', 'slug' => 'editar-pacientes', 'descripcion' => 'Permiso para editar pacientes', 'modulo_id' => $pacientes->id],
            ['nombre' => 'Eliminar Pacientes', 'slug' => 'eliminar-pacientes', 'descripcion' => 'Permiso para eliminar pacientes', 'modulo_id' => $pacientes->id],
        ];

        foreach ($pacientes_p as $permiso) {
            Permiso::create($permiso);
        }

        // Crear permisos para el módulo de aseguradoras
        $aseguradoras = Modulo::create(['nombre' => 'Aseguradoras']);
        $aseguradoras_p = [
            ['nombre' => 'Ver Aseguradoras', 'slug' => 'ver-aseguradoras', 'descripcion' => 'Permiso para ver aseguradoras', 'modulo_id' => $aseguradoras->id],
            ['nombre' => 'Crear Aseguradoras', 'slug' => 'crear-aseguradoras', 'descripcion' => 'Permiso para crear aseguradoras', 'modulo_id' => $aseguradoras->id],
            ['nombre' => 'Editar Aseguradoras', 'slug' => 'editar-aseguradoras', 'descripcion' => 'Permiso para editar aseguradoras', 'modulo_id' => $aseguradoras->id],
            ['nombre' => 'Eliminar Aseguradoras', 'slug' => 'eliminar-aseguradoras', 'descripcion' => 'Permiso para eliminar aseguradoras', 'modulo_id' => $aseguradoras->id],
        ];

        foreach ($aseguradoras_p as $permiso) {
            Permiso::create($permiso);
        }



        // Permisos para el módulo de Citas
        $citas = Modulo::create(['nombre' => 'Citas']);
        $citas_p = [
            ['nombre' => 'Ver Citas', 'slug' => 'ver-citas', 'descripcion' => 'Permiso para ver citas', 'modulo_id' => $citas->id],
            ['nombre' => 'Crear Citas', 'slug' => 'crear-citas', 'descripcion' => 'Permiso para crear citas', 'modulo_id' => $citas->id],
            ['nombre' => 'Editar Citas', 'slug' => 'editar-citas', 'descripcion' => 'Permiso para editar citas', 'modulo_id' => $citas->id],
            ['nombre' => 'Eliminar Citas', 'slug' => 'eliminar-citas', 'descripcion' => 'Permiso para eliminar citas', 'modulo_id' => $citas->id],
        ];
        Permiso::insert($citas_p);
        */


        $rol = Role::first();

        if ($rol) {
            $rol->permisos()->sync(Permiso::pluck('id')->toArray());
        }
    }
}
