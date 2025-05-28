<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $empresa=Empresa::firstOrCreate(
            ['ruc' => '1234567890123'], // Condición para buscar
            [
                'nombre' => 'Empresa Por Defecto',
                'correo' => 'admin@admin.com',
                'telefono' => '0987654321',
                'dominio' => 'localhost',
                'estado' => 1,
            ]
        );
        // Crear un rol por defecto
        $rol = Role::firstOrCreate(
            ['nombre' => 'Administrador', 'empresa_id' => $empresa->id], // Condición para buscar
            ['updated_at' => now()] // Campos adicionales si se crea
        );

        User::firstOrCreate(
            ['empresa_id' => $empresa->id, 'numero_documento' => '1234567890'], // Condición para buscar
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'role_id' => $rol->id,
                'tipo_documento_identidad_id' => 1,
                'estado' => true,
            ]
        );

        $this->command->info('Datos por defecto creados exitosamente.');
    }
}
//php artisan db:seed --class=DefaultDataSeeder