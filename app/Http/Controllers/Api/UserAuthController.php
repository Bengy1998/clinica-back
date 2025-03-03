<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
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
        $credentials = $request->only('email', 'password');

        // Intentar autenticar al usuario
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->responseErrorJson('Credenciales no válidas.', [], Response::HTTP_UNAUTHORIZED);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        $user->load('rol');

        // Verificar que el usuario pertenezca a la empresa correcta
        if ($user->empresa_id !== $request->empresa_id) {
            // Cerrar la sesión del usuario si no pertenece a la empresa
            Auth::logout();
            return $this->responseErrorJson('Credenciales no válidas.', [], Response::HTTP_UNAUTHORIZED);
        }

        // Devolver el token JWT
        return $this->responseJson([
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60, // Duración en segundos
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return $this->responseJsonMessageOk('Sesión cerrada correctamente.');
    }


    public function redirectToGoogle(Request $request)
    {
        Log::info('Redirecting to Google', ['request' => $request->all(), 'header' => $request->getHost(), 'header' => $request->header('Origin')]);
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ]);
       // return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        //try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // // Buscar usuario en la base de datos o crearlo
            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(12512414124142142), // Generar contraseña aleatoria
                    'email' => $googleUser->getEmail(),
                ]
            );

            // Generar token JWT
            $token = JWTAuth::fromUser($user);

            return redirect()->to("http://localhost:4200/login?token=$token");
       /*  } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo autenticar con Google'], 401);
        } */
    }
}
