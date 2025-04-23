<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\TipoDocumentoIdentidad; // Importar el modelo

class TipoDocumentoIdentidadController extends Controller
{
    use ResponseTrait;

    /**
     * Devuelve la lista de todos los tipos de documentos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tipos_documentos = TipoDocumentoIdentidad::where('estado', 1)->get(['id', 'nombre', 'codigo_corto']); // Obtener todos los registros
        return $this->responseJson($tipos_documentos);
    }
}
