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

        $empresa = Empresa::create([
            'nombre' => 'Empresa Por Defecto',
            'ruc' => '1234567890123',
            'correo' => 'admin@admin.com',
            'telefono' => '0987654321',
            'dominio' => '127.0.0.1',
            'estado' => true
        ]);
        // Crear un rol por defecto
        $rol = Role::create([
            'nombre' => 'Administrador',
            'empresa_id' => $empresa->id,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'), // Contraseña por defecto
            'empresa_id' => $empresa->id,
            'role_id' => $rol->id,
            'numero_documento' => '1234567890',
            'tipo_documento_identidad_id' => 1, // Asumiendo que el ID 1 es "Cédula de Identidad"
            'estado' => true
        ]);

        $this->command->info('Datos por defecto creados exitosamente.');
    }
}
//php artisan db:seed --class=DefaultDataSeeder