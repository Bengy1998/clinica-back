<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpresaRequest;
use App\Models\Empresa;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpresaController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $empresas = Empresa::all();
            return $this->responseJson($empresas);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(EmpresaRequest $request)
    {
        DB::beginTransaction();
        try {
            $empresa = Empresa::create([
                'nombre' => $request->input('nombre'),
                'ruc' => $request->input('ruc'),
                'correo' => $request->input('correo'),
                'telefono' => $request->input('telefono'),
                'dominio' => $request->input('dominio'),
                'estado' => true,
            ]);

            $rol = Role::create([
                'nombre' => 'Administrador',
                'empresa_id' => $empresa->id,
            ]);

            $permisos = Permiso::all();
            $rol->permisos()->sync($permisos->pluck('id')->toArray());

            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make($request->input('ruc')),
                'empresa_id' => $empresa->id,
                'role_id' => $rol->id,
                'tipo_documento_id' => 1,
                'estado' => true
            ]);

            DB::commit();
            return $this->responseJson($empresa, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            return $this->responseJson($empresa);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(EmpresaRequest $request, $id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            $empresa->update([
                'nombre' => $request->input('nombre'),
                'ruc' => $request->input('ruc'),
                'correo' => $request->input('correo'),
                'telefono' => $request->input('telefono'),
                'dominio' => $request->input('dominio'),
                'estado' => $request->input('estado', true),
            ]);
            return $this->responseJson($empresa, 'Empresa actualizada exitosamente.');
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
