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
            'id'                       => $this->id ?? '',
            'empresa_id'               => $this->empresa_id ?? '',
            'nombres'                  => $this->nombres ?? '',
            'apellido_paterno'         => $this->apellido_paterno ?? '',
            'apellido_materno'         => $this->apellido_materno ?? '',
            'tipo_documento_id'        => $this->tipo_documento_id ?? '',
            'nombre_completo'          => $this->apellido_paterno.' '.$this->apellido_materno.' ,'.$this->nombres,
            'tipo_documento_identidad_id' => $this->tipo_documento_identidad_id ?? '',
            'telefono'                 => $this->telefono ?? '',
            'email'                    => $this->email ?? '',
            'fecha_nacimiento'         => $this->fecha_nacimiento ?? '',
            'created_at'               => $this->created_at ?? '',
            'updated_at'               => $this->updated_at ?? ''
        ];
    }
}
