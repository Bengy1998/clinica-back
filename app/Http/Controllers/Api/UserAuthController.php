<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PermisosUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;

class UserAuthController extends Controller
{
    use ResponseTrait;

    public function login(LoginRequest $request)
    {
        // Obtener las credenciales
        $credentials = $request->only('email', 'password', 'empresa_id');
    
        // Intentar autenticar al usuario
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->responseErrorJson('Credenciales no válidas.', [], Response::HTTP_FORBIDDEN); // Cambiado a 403
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        $user->load('rol.permisos', 'tipo_documento', 'empresa');

        // Verificar que el usuario pertenezca a la empresa correcta
        if ($user->empresa_id !== $request->empresa_id) {
            // Cerrar la sesión del usuario si no pertenece a la empresa
            Auth::logout();
            return $this->responseErrorJson('Credenciales no válidas.', [], Response::HTTP_FORBIDDEN); // Cambiado a 403
        }

        // Obtener los permisos del rol del usuario
        $permisos = $user->rol->permisos;

        // Devolver el token JWT y los permisos
        return $this->responseJson([
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60, // Duración en segundos
            'permisos' => PermisosUserResource::collection($permisos),
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->responseJsonMessageOk('Sesión cerrada correctamente.');
    }
}
