<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaCalendarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Formatear título con especialidad y médico
        $nombreMedico = $this->medico ?
            trim($this->medico->nombre . ' ' . $this->medico->apellido_paterno . ' ' . $this->medico->apellido_materno)
            : 'Sin médico';

        $motivo = $this->motivo ? $this->motivo->descripcion : 'Sin motivo';
        $title = $motivo . ' - Dr. ' . $nombreMedico;

        // Formatear fechas para el calendario
        $fechaHora = $this->fecha . 'T' . $this->hora;
        $fechaHoraFin = $this->fecha . 'T' . $this->hora_fin;

        return [
            'id' => $this->id,
            'title' => $title,
            'start' => $fechaHora,
            'end' => $fechaHoraFin,
            'fecha' => $this->fecha,
            'hora' => date('g:i A', strtotime($this->hora)),
            'hora_fin' => $this->hora_fin ? date('g:i A', strtotime($this->hora_fin)) : '',
            'detalle' => $this->detalle ?? '',
            'especialidad' => $this->especialidad ? $this->especialidad->nombre : '',
            'medico' => $this->medico ? $nombreMedico : '',
            'estado' => $this->estado ? $this->estado->nombre : '',
            'paciente' => $this->paciente ? trim($this->paciente->nombres . ' ' . $this->paciente->apellido_paterno . ' ' . $this->paciente->apellido_materno) : '',
            'aseguradora' => $this->aseguradora ? $this->aseguradora->nombre : '',
            'motivo' => $this->motivo ? $this->motivo->descripcion : ''
        ];
    }
}
