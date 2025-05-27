<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacienteStoreRequest;
use App\Http\Requests\PacienteUpdateRequest;
use App\Http\Resources\PacienteResource;
use App\Http\Resources\TipoDocumentoIdentidadResource;
use App\Models\Paciente;
use App\Models\TipoDocumentoIdentidad;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PacienteController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        try {
            $pacientes = Paciente::when($request->filled('nombre_completo'), function ($query) use ($request) {
                $query->whereRaw("CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ['%' . $request->nombre_completo . '%']);
            })
                ->when($request->filled('search'), function ($query) use ($request) {
                    $search = $request->input('search');
                    $query->where(function ($query) use ($search) {
                        $query->whereRaw("CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ['%' . $search . '%'])
                            ->orWhere('numero_documento_identidad', 'LIKE', '%' . $search . '%');
                    });
                })
                ->when($request->filled('sort_field') && $request->filled('sort_order'), function ($query) use ($request) {
                    $sortField = $request->input('sort_field');
                    $sortOrder = $request->input('sort_order');
                    $query->orderBy($sortField, $sortOrder);
                }, function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->paginate(10);

            return $this->responseJson([
                'data' => PacienteResource::collection($pacientes),
                'meta'  => [
                    'total'         => $pacientes->total(),
                    'current_page'  => $pacientes->currentPage(),
                    'per_page'      => $pacientes->perPage(),
                    'last_page'     => $pacientes->lastPage(),
                    'from'          => $pacientes->firstItem(),
                    'to'            => $pacientes->lastItem(),
                ],
                'links' => [
                    'first' => $pacientes->url(1),
                    'last'  => $pacientes->url($pacientes->lastPage()),
                    'prev'  => $pacientes->previousPageUrl(),
                    'next'  => $pacientes->nextPageUrl(),
                ]
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PacienteStoreRequest $request)
    {
        try {
            $data = $request->only([
                'empresa_id',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'numero_documento_identidad',
                'tipo_documento_identidad_id',
                'telefono',
                'email',
                'fecha_nacimiento'
            ]);

            $paciente = Paciente::create($data);
            return $this->responseJson(new PacienteResource($paciente), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {

        try {
            return $this->responseJson(new PacienteResource($paciente));
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PacienteUpdateRequest $request, Paciente $paciente)
    {
        try {
            $data = $request->only([
                'empresa_id',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'numero_documento_identidad',
                'tipo_documento_identidad_id',
                'telefono',
                'email',
                'fecha_nacimiento'
            ]);
            $paciente->update($data);
            return $this->responseJson(new PacienteResource($paciente));
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        try {
            $paciente->delete();
            return $this->responseJson([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function TipoDocumento()
    {
        try {
            $list_motivos_cita = TipoDocumentoIdentidad::all();
            return $this->responseJson([
                'data' => TipoDocumentoIdentidadResource::collection($list_motivos_cita)
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
