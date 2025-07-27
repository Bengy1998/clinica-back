<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitaStoreRequest;
use App\Http\Requests\CitaUpdateRequest;
use App\Http\Resources\CitaCalendarioResource;
use App\Http\Resources\CitaEstadoResource;
use App\Http\Resources\CitaResource;
use App\Models\Cita;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CitaController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {

            $citas = Cita::with(['paciente', 'medico', 'especialidad', 'aseguradora'])
                ->when($request->filled('paciente_nombre'), function ($query) use ($request) {
                    $query->whereHas('paciente', function ($query) use ($request) {
                        $query->where('nombre', 'like', '%' . $request->input('paciente_nombre') . '%');
                    });
                })
                ->when($request->filled('numero_documento_identidad'), function ($query) use ($request) {
                    $query->whereHas('paciente', function ($query) use ($request) {
                        $query->where('numero_documento_identidad', 'like', '%' . $request->input('numero_documento_identidad') . '%');
                    });
                })
                ->when($request->filled('paciente_id'), function ($query) use ($request) {
                    $query->where('paciente_id', $request->input('paciente_id'));
                })
                ->when($request->filled('aseguradora_id'), function ($query) use ($request) {
                    $query->where('aseguradora_id', $request->input('aseguradora_id'));
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
                'data' => CitaResource::collection($citas),
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
    public function store(CitaStoreRequest $request)
    {
        try {
            $data = $request->only([
                'paciente_id',
                'aseguradora_id',
                'especialidad_id',
                'medico_id',
                'fecha',
                'hora',
                'empresa_id',
            ]);

            $cita = Cita::create($data);
            return $this->responseJson(new CitaResource($cita), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
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
    public function update(CitaUpdateRequest $request, Cita $cita)
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
    public function destroy(Cita $cita)
    {
        try {
            $cita->delete();
            return $this->responseJson(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtener citas por mes y año
     */
    public function citasPorMesAnio(Request $request)
    {
        try {

            $mes = $request->input('mes', now()->month);
            $anio = $request->input('anio', now()->year);

            $citas = Cita::with(['paciente', 'medico', 'especialidad', 'aseguradora', 'estado', 'motivo'])
                ->whereYear('fecha', $anio)
                ->whereMonth('fecha', $mes)
                ->when($request->filled('empresa_id'), function ($query) use ($request) {
                    $query->where('empresa_id', $request->input('empresa_id'));
                })
                ->when($request->filled('especialidad_id'), function ($query) use ($request) {
                    $query->where('especialidad_id', $request->input('especialidad_id'));
                })
                ->when($request->filled('medico_id'), function ($query) use ($request) {
                    $query->where('medico_id', $request->input('medico_id'));
                })
                ->when($request->filled('estado_id'), function ($query) use ($request) {
                    $query->where('estado_id', $request->input('estado_id'));
                })
                ->orderBy('fecha', 'asc')
                ->orderBy('hora', 'asc')
                ->get();
            
            return $this->responseJson(CitaCalendarioResource::collection($citas));
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
