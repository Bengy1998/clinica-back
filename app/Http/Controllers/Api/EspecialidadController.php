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
    public function index()
    {
        try {
            $citas = Especialidad::all();

            return $this->responseJson([
                'data' => EspecialidadResource::collection($citas),
                /*'meta'  => [
                    'total'         => $citas->total(),
                    'current_page'  => $citas->currentPage(),
                    'per_page'      => $citas->perPage(),
                    'last_page'     => $citas->lastPage(),
                    'from'          => $citas->firstItem(),
                    'to'            => $citas->lastItem(),
                ],
                'links' => [
                    'first' => $citas->url(1),
                    'last'  => $citas->url($citas->lastPage()),
                    'prev'  => $citas->previousPageUrl(),
                    'next'  => $citas->nextPageUrl(),
                ]*/
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
