<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AtencionResource;
use App\Models\Atencion;
use App\Traits\ResponseTrait;
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

            $atenciones = Atencion::with(['paciente', 'cita', 'medico', 'especialidad', 'aseguradora'])
                ->when(
                    $request->filled('cita_id'), function ($query) use ($request) {
                        $query->where('cita_id', $request->input('cita_id'));
                    }
                )
                ->when($request->filled('paciente_nombre'), function ($query) use ($request) {
                    $query->whereHas('cita.paciente', function ($query) use ($request) {
                        $query->where('nombre', 'like', '%' . $request->input('paciente_nombre') . '%');
                    });
                })
                ->when($request->filled('numero_documento_identidad'), function ($query) use ($request) {
                    $query->whereHas('cita.paciente', function ($query) use ($request) {
                        $query->where('numero_documento_identidad', 'like', '%' . $request->input('numero_documento_identidad') . '%');
                    });
                })
                ->when($request->filled('paciente_id'), function ($query) use ($request) {
                    $query->whereHas('cita.paciente', function ($query) use ($request) {
                        $query->where('paciente_id', 'like', '%' . $request->input('paciente_id') . '%');
                    });
                })
                ->when($request->filled('aseguradora_id'), function ($query) use ($request) {
                    $query->whereHas('cita.aseguradora', function ($query) use ($request) {
                        $query->where('aseguradora_id', 'like', '%' . $request->input('aseguradora_id') . '%');
                    });
                })


          
              
                ->when($request->filled('especialidad_id'), function ($query) use ($request) {
                    $query->where('especialidad_id', $request->input('especialidad_id'));
                })
                ->when($request->filled('medico_id'), function ($query) use ($request) {
                    $query->where('medico_id', $request->input('medico_id'));
                })
                ->when($request->filled('fecha'), function ($query) use ($request) {
                    $query->where('fecha', $request->input('fecha'));
                })
                ->paginate(10);

            return $this->responseJson([
                'data' => AtencionResource::collection($atenciones),
                'meta'  => [
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
        try {
            return $this->responseJson(new CitaResource($cita));
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $data = $request->only([
                'nombre',
                'paciente_id',
                'aseguradora_id',
                'especialidad_id',
                'medico_id',
                'fecha',
                'hora',
                'empresa_id',
                'estado_id'
            ]);
            $cita->update($data);
            return $this->responseJson(new CitaResource($cita), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cita->delete();
            return $this->responseJson(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
