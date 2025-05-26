<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\CitaEstadoResource;
use App\Models\CitaEstado;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EstadoCitaController extends Controller
{
    use ResponseTrait;

    public function EstadoCita()
    {
        try {
            $list_estados_cita = CitaEstado::all();
            return $this->responseJson([
                'data' => CitaEstadoResource::collection($list_estados_cita)
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
