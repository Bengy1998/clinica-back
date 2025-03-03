<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LineaAereaResource;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\ProveedorResource;

class AvisoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'nro_vuelo'                 => $this->nro_vuelo ?? '',
            'linea_id'                  => $this->linea_id ?? '',
            'hawg'                      => $this->hawg ?? '',
            'origen'                    => $this->origen ?? '',
            'cliente_id'                => $this->cliente_id ?? '',
            'proveedor_id'              => $this->proveedor_id ?? '',
            'contenido'                 => $this->contenido ?? '',
            'bultos'                    => $this->bultos ?? '',
            'peso'                      => $this->peso ?? '',
            'mrn'                       => $this->mrn ?? '',
            'secuencial'                => $this->secuencial ?? '',
            'nro_anviso'                => $this->nro_anviso ?? '',
            'tipo_flete_term'           => $this->tipo_flete_term ?? '',
            'tipo_flete_term_nombre'    => $this->tipo_flete_term_nombre,
            'tipo_aviso_nombre'         => $this->tipo_aviso_nombre,
            'tipo_aviso'                => $this->tipo_aviso,
            'eta_aprox'                 => $this->eta_aprox ?? '',  
            'destino'                   => $this->destino ?? '',  
            'created_at'                => $this->created_at ? $this->created_at->toDateTimeString() : '',
            'updated_at'                => $this->updated_at ? $this->updated_at->toDateTimeString() : '',
            // Relacionados (si se han cargado)
            'linea'                     => new LineaAereaResource($this->whenLoaded('linea')),
            'cliente'                   => new ClienteResource($this->whenLoaded('cliente')),
            'proveedor'                 => new ProveedorResource($this->whenLoaded('proveedor')),
        ];
    }
}
