<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atencion;
use App\Traits\ResponseTrait;
use App\Http\Resources\AtencionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AtencionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        try {

            $atenciones = Atencion::with('cita.paciente')->when($request->filled('cita_id'), function ($query) use ($request) {
                $query->where('cita_id',  $request->input('cita_id') );
            })
               
                ->when($request->filled('fecha_atencion'), function ($query) use ($request) {
                    $query->where('fecha_atencion', $request->input('fecha_atencion'));
                })
                ->paginate(10);

            return $this->responseJson([
                'data' => AtencionResource::collection($atenciones),
                'meta'  => [
                    'total'         => $atenciones->total(),
                    'current_page'  => $atenciones->currentPage(),
                    'per_page'      => $atenciones->perPage(),
                    'last_page'     => $atenciones->lastPage(),
                    'from'          => $atenciones->firstItem(),
                    'to'            => $atenciones->lastItem(),
                ],
                'links' => [
                    'first' => $atenciones->url(1),
                    'last'  => $atenciones->url($atenciones->lastPage()),
                    'prev'  => $atenciones->previousPageUrl(),
                    'next'  => $atenciones->nextPageUrl(),
                ]
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
