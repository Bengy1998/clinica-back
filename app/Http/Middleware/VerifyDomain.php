<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyDomain
{

    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el dominio de la solicitud
        $domain = $request->getHost();
        // Buscar la empresa por dominio
        $empresa = Empresa::where([
            'dominio' =>  $domain,
            'estado' => true
        ])->first();

        // Si no existe la empresa, devolver un error
        if (!$empresa) {
            return $this->responseErrorJson('Empresa desactivada, por favor comunicate por un administrador'. '/ domain:'.$domain, [], Response::HTTP_FORBIDDEN);
        }
        // Guardar el ID de la empresa en la solicitud para usarlo más tarde
        $request->merge(['empresa_id' => $empresa->id]);

        return $next($request);
    }
}
