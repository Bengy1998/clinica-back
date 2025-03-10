<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AseguradoraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'         => $this->id ?? '',
            'nombre'     => $this->nombres ?? '',
            'correo'     => $this->correo ?? '',
            'ruc'        => $this->ruc ?? '',
            'telefono'   => $this->telefono ?? '',
            'email'      => $this->email ?? '',
            'empresa_id' => $this->empresa_id ?? '',
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
        ];
    }

}
