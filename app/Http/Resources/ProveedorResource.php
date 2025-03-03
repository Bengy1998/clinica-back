<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClienteResource;

class ProveedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nombre'     => $this->nombre,
            'cliente_id' => $this->cliente_id,
            'cliente'    => new ClienteResource($this->whenLoaded('cliente')),
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
        ];
    }
}
