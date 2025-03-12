<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id ?? '',
            'nombre'          => $this->nombre ?? '',
            'paciente_id'     => $this->paciente_id ?? '',
            'aseguradora_id'  => $this->aseguradora_id ?? '',
            'especialidad_id' => $this->especialidad_id ?? '',
            'medico_id'       => $this->medico_id ?? '',
            'fecha'           => $this->fecha ?? '',
            'created_at'      => $this->created_at ?? '',
            'updated_at'      => $this->updated_at ?? '',
        ];
    }
}
