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
            'paciente_id'     => $this->paciente_id ?? '',
            'aseguradora_id'  => $this->aseguradora_id ?? '',
            'nombre_aseguradora' => $this->aseguradora->nombre ?? '',
            'especialidad_id' => $this->especialidad_id ?? '',
            'nombre_especialidad' => $this->especialidad->nombre ?? '',
            'medico_id'       => $this->medico_id ?? '',
            'fecha'           => $this->fecha ?? '',
            'created_at'      => $this->created_at ?? '',
            'updated_at'      => $this->updated_at ?? '',
            'nombre_estado' => $this->estado->nombre ?? '',
            'motivo_cita_id' => $this->motivo_cita_id ?? '',
            'nombre_medico' => trim(($this->medico->apellido_paterno ?? '') . ' ' . ($this->medico->apellido_materno ?? '') . ', ' . ($this->medico->nombre ?? '')),
            'nombre_completo_paciente' => trim(($this->paciente->apellido_paterno ?? '') . ' ' . ($this->paciente->apellido_materno ?? '') . ', ' . ($this->paciente->nombres ?? '')),
            'tipo_documento_identidad' => $this->paciente->tipoDocumento->nombre ?? '',
            'numero_documento_identidad_paciente' => $this->paciente->numero_documento_identidad ?? '',
            'fecha' => $this->fecha ?? '',
            'hora' => $this->hora ?? '',
            'detalle' => $this->detalle ?? '',
            'hora_fin' => $this->hora_fin ?? '',
            'estado_id' => $this->estado_id ?? '',

        ];
    }
}
