<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacienteStoreRequest;
use App\Http\Requests\PacienteUpdateRequest;
use App\Http\Resources\PacienteResource;
use App\Models\Paciente;
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
                $nombreCompleto = $request->input('nombre_completo');
                $query->where(function ($query) use ($nombreCompleto) {
                    $query->where('nombres', 'like', '%' . $nombreCompleto . '%')
                        ->orWhere('apellido_paterno', 'like', '%' . $nombreCompleto . '%')
                        ->orWhere('apellido_materno', 'like', '%' . $nombreCompleto . '%');
                });
            })
                ->when($request->filled('nombres'), function ($query) use ($request) {
                    $query->where('nombres', 'like', '%' . $request->input('nombres') . '%');
                })
                ->when($request->filled('apellido_paterno'), function ($query) use ($request) {
                    $query->where('apellido_paterno', 'like', '%' . $request->input('apellido_paterno') . '%');
                })
                ->when($request->filled('apellido_materno'), function ($query) use ($request) {
                    $query->where('apellido_materno', 'like', '%' . $request->input('apellido_materno') . '%');
                })
                ->when($request->filled('tipo_documento_identidad_id'), function ($query) use ($request) {
                    $query->where('tipo_documento_identidad_id', $request->input('tipo_documento_identidad_id'));
                })
                ->when($request->filled('numero_documento_identidad'), function ($query) use ($request) {
                    $query->where('numero_documento_identidad', 'like', '%' . $request->input('numero_documento_identidad') . '%');
                })
                ->when($request->filled('telefono'), function ($query) use ($request) {
                    $query->where('telefono', 'like', '%' . $request->input('telefono') . '%');
                })
                ->when($request->filled('correo'), function ($query) use ($request) {
                    $query->where('correo', 'like', '%' . $request->input('correo') . '%');
                })
                ->when($request->filled('fecha_nacimiento'), function ($query) use ($request) {
                    $query->whereDate('fecha_nacimiento', $request->input('fecha_nacimiento'));
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
            $pacientes = Paciente::create($request->all());
            return $this->responseJson(new PacienteResource($pacientes), Response::HTTP_CREATED);
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
            $paciente->update($request->all());
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
}
