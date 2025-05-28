<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermisosSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el módulo "Empresa"
        $moduloEmpresa = Modulo::firstOrCreate(
            ['nombre' => 'Empresa'], // Condición para buscar
            ['updated_at' => now()] // Campos adicionales si se crea
        );

        // Crear permisos para el módulo "Empresa"
        $permisosEmpresa = [
            ['nombre' => 'Ver Empresa', 'slug' => 'ver-empresa', 'descripcion' => 'Permiso para ver empresas', 'modulo_id' => $moduloEmpresa->id],
            ['nombre' => 'Crear Empresa', 'slug' => 'crear-empresa', 'descripcion' => 'Permiso para crear empresas', 'modulo_id' => $moduloEmpresa->id],
            ['nombre' => 'Editar Empresa', 'slug' => 'editar-empresa', 'descripcion' => 'Permiso para editar empresas', 'modulo_id' => $moduloEmpresa->id],
            ['nombre' => 'Eliminar Empresa', 'slug' => 'eliminar-empresa', 'descripcion' => 'Permiso para eliminar empresas', 'modulo_id' => $moduloEmpresa->id],
        ];

        foreach ($permisosEmpresa as $permiso) {
            Permiso::firstOrCreate(
                ['slug' => $permiso['slug']], // Condición para buscar
                $permiso // Datos para crear si no existe
            );
        }

        // Crear el módulo "Pacientes"
        $moduloPacientes = Modulo::firstOrCreate(
            ['nombre' => 'Pacientes'], // Condición para buscar
            ['updated_at' => now()] // Campos adicionales si se crea
        );

        // Crear permisos para el módulo "Pacientes"
        $permisosPacientes = [
            ['nombre' => 'Ver Pacientes', 'slug' => 'ver-pacientes', 'descripcion' => 'Permiso para ver pacientes', 'modulo_id' => $moduloPacientes->id],
            ['nombre' => 'Crear Pacientes', 'slug' => 'crear-pacientes', 'descripcion' => 'Permiso para crear pacientes', 'modulo_id' => $moduloPacientes->id],
            ['nombre' => 'Editar Pacientes', 'slug' => 'editar-pacientes', 'descripcion' => 'Permiso para editar pacientes', 'modulo_id' => $moduloPacientes->id],
            ['nombre' => 'Eliminar Pacientes', 'slug' => 'eliminar-pacientes', 'descripcion' => 'Permiso para eliminar pacientes', 'modulo_id' => $moduloPacientes->id],
        ];

        foreach ($permisosPacientes as $permiso) {
            Permiso::firstOrCreate(
                ['slug' => $permiso['slug']], // Condición para buscar
                $permiso // Datos para crear si no existe
            );
        }

        $moduloCitas = Modulo::firstOrCreate(
            ['nombre' => 'Citas'], // Condición para buscar
            ['updated_at' => now()] // Campos adicionales si se crea
        );

        // Crear permisos para el módulo "Citas"
        $permisosCitas = [
            ['nombre' => 'Ver Citas', 'slug' => 'ver-citas', 'descripcion' => 'Permiso para ver citas', 'modulo_id' => $moduloCitas->id],
            ['nombre' => 'Crear Citas', 'slug' => 'crear-citas', 'descripcion' => 'Permiso para crear citas', 'modulo_id' => $moduloCitas->id],
            ['nombre' => 'Editar Citas', 'slug' => 'editar-citas', 'descripcion' => 'Permiso para editar citas', 'modulo_id' => $moduloCitas->id],
            ['nombre' => 'Eliminar Citas', 'slug' => 'eliminar-citas', 'descripcion' => 'Permiso para eliminar citas', 'modulo_id' => $moduloCitas->id],
        ];

        foreach ($permisosCitas as $permiso) {
            Permiso::firstOrCreate(
                ['slug' => $permiso['slug']], // Condición para buscar
                $permiso // Datos para crear si no existe
            );
        }
        // Asociar todos los permisos al rol con role_id = 1
        $role = Role::find(1); // Obtener el rol con ID 1
        if ($role) {
            $permisos = Permiso::all(); // Obtener todos los permisos
            $role->permisos()->sync($permisos->pluck('id')->toArray()); // Asociar todos los permisos al rol
        }















    }
}