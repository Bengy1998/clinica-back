<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();
            
            $permissions = Cache::remember("permissions_{$user->id}", 1440, function () use ($user) {
                return $user->rol->permisos->pluck('slug')->unique()->toArray();
            });

            $routeName = $request->route()->getName();

            if (!in_array($routeName, $permissions)) {
                return $this->responseErrorJson('Accion denegada', [], 403);
            }
        } catch (JWTException $e) {
            return $this->responseErrorJson('Token invalido', [], 401);
        }

        return $next($request);
    }
}
