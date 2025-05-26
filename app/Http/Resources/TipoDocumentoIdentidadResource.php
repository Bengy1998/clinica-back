<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoDocumentoIdentidadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id ?? '',
            'nombre'       => $this->nombre ?? '',
            'codigo_corto' => $this->codigo_corto ?? ''
        ];
    }
}
