<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TipoDocumentoIdentidadResource;
use App\Models\TipoDocumentoIdentidad;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TipoDocumentoIdentidadController extends Controller
{
    use ResponseTrait;

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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TipoDocumentoIdentidadResource;
use App\Models\TipoDocumentoIdentidad;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TipoDocumentoIdentidadController extends Controller
{
    use ResponseTrait;

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
