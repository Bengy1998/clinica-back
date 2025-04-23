<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PacienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                            => $this->id ?? '',
            'nombres'                       => $this->nombres ?? '',
            'apellido_paterno'              => $this->apellido_paterno ?? '',
            'apellido_materno'              => $this->apellido_materno ?? '',
            'nombre_completo'               => $this->nombre_completo,
            'tipo_documento_identidad_id'   => $this->tipo_documento_identidad_id ?? '',
            'telefono'                      => $this->telefono ?? '',
            'email'                         => $this->email ?? '',
            'fecha_nacimiento'              => $this->fecha_nacimiento ?? '',
            'tipo_documento_identidad'      => $this->tipo_documento?->codigo_corto ?? '',
            'numero_documento_identidad'    => $this->numero_documento_identidad ?? '',
        ];
    }
}
