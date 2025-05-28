<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', ['--env' => 'testing']);
        $this->artisan('db:seed', ['--env' => 'testing']);
    }

    #[Test]

    public function login_fails_with_invalid_credentials()
    {
        $empresa = Empresa::first();

        $response = $this->postJson('/api/login', [
            'email' => 'noexiste@example.com',
            'password' => 'incorrecta',
            'empresa_id' => $empresa?->id ?? 1,
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Credenciales no válidas.']);
    }


    #[Test]
    public function login_fails_with_wrong_empresa_id()
    {
        $user = User::first();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password_correcta',
            'empresa_id' => 999999,
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Credenciales no válidas.']);
    }

    #[Test]
    public function login_succeeds_with_valid_credentials()
    {
        $user = User::first();
        $empresa = Empresa::first();

        $response = $this->withHeaders([
            'X-Test-Domain' => $empresa->dominio,
        ])->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'admin',
            'empresa_id' => $user->empresa_id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token',
                    'token_type',
                    'expires_in',
                    'permisos'
                ]
            ]);
    }
}
