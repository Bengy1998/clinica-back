<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Aquí se incluyen todos los campos del modelo Cliente.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id ?? '',
            'nombre'     => $this->nombre ?? '',
            'correo'      => $this->correo ?? '',
            'ruc'        => $this->ruc ?? '',
            'contacto'   => $this->contacto ?? '',
            'direccion'  => $this->direccion ?? '',
            'telefono'   => $this->telefono ?? '',
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
        ];
    }
}
