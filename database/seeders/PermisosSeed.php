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
    {
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

        // Asociar permisos al primer rol
        $rol = Role::first();
        if ($rol) {
            $rol->permisos()->sync(Permiso::pluck('id')->toArray());
        }
    }
}
