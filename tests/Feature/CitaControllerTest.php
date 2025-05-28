<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\Aseguradora;
use App\Models\CitaEstado;
use App\Models\MotivoCita;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitaControllerTest extends TestCase
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


    #[\PHPUnit\Framework\Attributes\Test('Listado de Citas GREEN')]
    public function listado_cita_green()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        Cita::factory()->count(3)->create(); // Crear 3 citas de prueba

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token", // Usar el token para autenticar la solicitud
        ])->getJson('/api/citas');



        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test('Listado de Citas RED')]
    public function listado_cita_red()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        Cita::factory()->count(3)->create(); // Crear 3 citas de prueba


        $response = $this->withHeaders([
            'Authorization' => "Bearer ", // Usar el token para autenticar la solicitud
        ])->getJson('/api/citas');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'nombre',
                        'paciente_id',
                        'aseguradora_id',
                        'nombre_aseguradora',
                        'especialidad_id',
                        'nombre_especialidad',
                        'medico_id',
                        'fecha',
                        'created_at',
                        'updated_at',
                        'nombre_estado',
                        'nombre_medico',
                        'nombre_completo_paciente',
                        'tipo_documento_identidad',
                        'numero_documento_identidad_paciente',
                    ]
                ],
                'meta' => [
                    'total',
                    'current_page',
                    'per_page',
                    'last_page',
                    'from',
                    'to',
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
            ],
        ]);

    }




    #[\PHPUnit\Framework\Attributes\Test]
    public function creacion_cita_green()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        $paciente = Paciente::factory()->create();
        $medico = Medico::factory()->create();
        $especialidad = Especialidad::first();
        $aseguradora = Aseguradora::first();
        $estado = CitaEstado::first();
        $motivo = MotivoCita::first();;


        $data = [
            'empresa_id' => 1,
            'paciente_id' => $paciente->id,
            'nombre' => 'x',
            'aseguradora_id' => $aseguradora->id,
            'medico_id' => $medico->id,
            'especialidad_id' => $especialidad->id,
            'fecha' => now()->toDateString(),
            'hora' => now()->toTimeString(),
            'estado_id' => $estado->id,
            'motivo_cita_id' => $motivo->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token", // Usar el token para autenticar la solicitud
        ])->postJson('/api/citas', $data);

        //$response->dump(); // Imprimir la respuesta completa para depuración

        $response->assertStatus(201);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function creacion_cita_red()
    {
        $token = $this->authenticateUser(); // Autenticar al usuario y obtener el token

        $paciente = Paciente::factory()->create();
        $medico = Medico::factory()->create();
        $especialidad = Especialidad::first();
        $aseguradora = Aseguradora::first();
        $estado = CitaEstado::first();
        $motivo = MotivoCita::first();;


        $data = [


            'estado_id' => $estado->id,
            'motivo_cita_id' => $motivo->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token", // Usar el token para autenticar la solicitud
        ])->postJson('/api/citas', $data);

        //$response->dump(); // Imprimir la respuesta completa para depuración

        $response->assertStatus(201);
    }
}
