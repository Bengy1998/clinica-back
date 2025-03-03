<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LineaAereaResource extends JsonResource
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
            'empresa_id' => $this->empresa_id,
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
        ];
    }
}
