<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MedicoController extends Controller
{
    use ResponseTrait;

    /**
     * Búsqueda de médicos para select
     */
    public function select(Request $request)
    {
        try {
            $list_medicos = Medico::when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search) {
                    $query->where('nombre', 'like', '%' . $search . '%')
                        ->orWhere('apellido_paterno', 'like', '%' . $search . '%')
                        ->orWhere('apellido_materno', 'like', '%' . $search . '%')
                        ->orWhereRaw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ['%' . $search . '%']);
                });
            })->limit(20)->get();

            // Formatear la respuesta para el select
            $medicos_formatted = $list_medicos->map(function ($medico) {
                return [
                    'id' => $medico->id,
                    'nombre_completo' => trim($medico->nombre . ' ' . $medico->apellido_paterno . ' ' . $medico->apellido_materno),
                    'numero_documento_identidad' => $medico->numero_documento_identidad
                ];
            });

            return $this->responseJson($medicos_formatted);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
