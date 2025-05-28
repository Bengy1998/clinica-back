<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar migraciones y seeders para el entorno de pruebas
        $this->artisan('migrate:fresh', ['--env' => 'testing']);
        $this->artisan('db:seed', ['--env' => 'testing']);
    }

    protected function authenticateUser()
    {
        $user = User::first(); // Obtener el primer usuario de la base de datos
        $empresa = Empresa::first(); // Obtener la primera empresa de la base de datos

        $response = $this->withHeaders([
            'X-Test-Domain' => $empresa->dominio,
        ])->postJson('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'admin', // Asegúrate de que esta contraseña sea válida
            'empresa_id' => 1,
        ]);

        $response->assertStatus(200); // Asegúrate de que el login sea exitoso

        return $response->json('data.token'); // Retorna el token de autenticación
    }

    #[\PHPUnit\Framework\Attributes\Test('Listado de Pacientes')]
    public function it_can_list_pacientes()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        Paciente::factory()->count(3)->create(); // Crear 3 pacientes de prueba

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token", // Usar el token para autenticar la solicitud
        ])->getJson('/api/pacientes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'nombres',
                            'apellido_paterno',
                            'apellido_materno',
                            'numero_documento_identidad',
                            'tipo_documento_identidad_id',
                            'telefono',
                            'email',
                            'fecha_nacimiento',
                        ]
                    ],
                    'meta',
                    'links',
                ],
            ]);
    }


    #[\PHPUnit\Framework\Attributes\Test('Listado de Pacientes 2')]
    public function it_cant_list_pacientes()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        Paciente::factory()->count(3)->create(); // Crear 3 pacientes de prueba

        $response = $this->withHeaders([
            'Authorizationxxxxx' => "Bearer", // Usar el token para autenticar la solicitud
        ])->getJson('/api/pacientes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'nombres',
                            'apellido_paterno',
                            'apellido_materno',
                            'numero_documento_identidad',
                            'tipo_documento_identidad_id',
                            'telefono',
                            'email',
                            'fecha_nacimiento',
                        ]
                    ],
                    'meta',
                    'links',
                ],
            ]);
    }

    #[\PHPUnit\Framework\Attributes\Test('Creación de Paciente con Datos Válidos')]
    public function it_can_create_a_paciente()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        $data = [
            'empresa_id' => 1,
            'nombres' => 'Juan',
            'apellido_paterno' => 'Perez',
            'apellido_materno' => 'Gomez',
            'numero_documento_identidad' => '12345678',
            'tipo_documento_identidad_id' => 1,
            'telefono' => '987654321',
            'email' => 'juan.perez@example.com',
            'fecha_nacimiento' => '1990-01-01',
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token", // Usar el token para autenticar la solicitud
        ])->postJson('/api/pacientes', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'nombres',
                    'apellido_paterno',
                    'apellido_materno',
                    'numero_documento_identidad',
                    'tipo_documento_identidad_id',
                    'telefono',
                    'email',
                    'fecha_nacimiento',
                ]
            ])
            ->assertJsonFragment([
                'nombres' => 'Juan',
                'apellido_paterno' => 'Perez',
                'apellido_materno' => 'Gomez',
                'numero_documento_identidad' => '12345678',
                'telefono' => '987654321',
                'email' => 'juan.perez@example.com',
            ]);
    }

    #[\PHPUnit\Framework\Attributes\Test('Creación de Paciente con Datos Inválidos')]
    public function it_fails_to_create_a_paciente_with_invalid_data()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        $data = [
            'empresa_id' => 1,
            'nombres' => '', // Campo vacío
            'apellido_paterno' => '', // Campo vacío
            'numero_documento_identidad' => '123', // Número inválido
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token", // Usar el token para autenticar la solicitud
        ])->postJson('/api/pacientes', $data);

        $response->assertStatus(422) // Código de error de validación
            ->assertJsonValidationErrors(['nombres', 'apellido_paterno', 'numero_documento_identidad']);
    }
}