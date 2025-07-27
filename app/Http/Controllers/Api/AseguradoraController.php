<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AseguradoraStoreRequest;

use App\Http\Requests\AseguradoraUpdateRequest;
use App\Http\Resources\AseguradoraResource;
use App\Models\Aseguradora;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AseguradoraController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {

        try {

            $aseguradoras = Aseguradora::when($request->filled('nombre'), function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->input('nombre') . '%');
            })
                ->when($request->filled('ruc'), function ($query) use ($request) {
                    $query->where('ruc', 'like', '%' . $request->input('ruc') . '%');
                })
                ->when($request->filled('empresa_id'), function ($query) use ($request) {
                    $query->where('empresa_id', $request->input('empresa_id'));
                })
                ->paginate(10);

            return $this->responseJson([
                'data' => AseguradoraResource::collection($aseguradoras),
                'meta'  => [
                    'total'         => $aseguradoras->total(),
                    'current_page'  => $aseguradoras->currentPage(),
                    'per_page'      => $aseguradoras->perPage(),
                    'last_page'     => $aseguradoras->lastPage(),
                    'from'          => $aseguradoras->firstItem(),
                    'to'            => $aseguradoras->lastItem(),
                ],
                'links' => [
                    'first' => $aseguradoras->url(1),
                    'last'  => $aseguradoras->url($aseguradoras->lastPage()),
                    'prev'  => $aseguradoras->previousPageUrl(),
                    'next'  => $aseguradoras->nextPageUrl(),
                ]
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(AseguradoraUpdateRequest $request, Aseguradora $aseguradora)
    {
        try {
            $aseguradora->update($request->all());
            return $this->responseJson(new AseguradoraResource($aseguradora));
        } catch (\Throwable $th) {
            return $this->responseServerError($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Aseguradora $aseguradoras)
    {
        try {
            return $this->responseJson(new AseguradoraResource($aseguradoras));
        } catch (\Throwable $th) {
            return $this->responseServerError($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function store(AseguradoraStoreRequest $request)
    {
        try {
            $aseguradoras = Aseguradora::create($request->all());
            return $this->responseJson(new AseguradoraResource($aseguradoras), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->responseServerError($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function destroy(Aseguradora $aseguradora)
    {
        try {
            $aseguradora->delete();
            return $this->responseJson(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return $this->responseServerError($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function select(Request $request)
    {
        try {
            $list_aseguradoras = Aseguradora::when($request->filled('search'), function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->input('search') . '%');
            })->limit(20)->get();
            return $this->responseJson(AseguradoraResource::collection($list_aseguradoras));
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
