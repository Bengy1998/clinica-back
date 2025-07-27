<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EspecialidadResource;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;

class EspecialidadController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function getEspecialidad()
    {

        try {
            $especialidad = Especialidad::all();

            return $this->responseJson(EspecialidadResource::collection($especialidad));
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
