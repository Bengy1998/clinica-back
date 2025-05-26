<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MotivoCitaResource;
use App\Models\MotivoCita;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MotivoCitaController extends Controller
{
    use ResponseTrait;


    public function MotivoCita()
    {
        try {
            $list_motivos_cita = MotivoCita::all();
            return $this->responseJson([
                'data' => MotivoCitaResource::collection($list_motivos_cita)
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
