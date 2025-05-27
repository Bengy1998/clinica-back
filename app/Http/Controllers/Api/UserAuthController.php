<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PermisosUserResource;
use App\Http\Resources\UserResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthController extends Controller
{
    use ResponseTrait;

    public function login(LoginRequest $request)
    {
       
        $credentials = $request->only('email', 'password', 'empresa_id');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->responseErrorJson('Credenciales no válidas.', [], Response::HTTP_FORBIDDEN);
        }

        $user = Auth::user();
        $user->load('rol.permisos', 'tipo_documento', 'empresa');

     
        if ($user->empresa_id !== $request->empresa_id) {
            Auth::logout();
            return $this->responseErrorJson('Credenciales no válidas.', [], Response::HTTP_FORBIDDEN);
        }

        $permisos = $user->rol->permisos;

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
