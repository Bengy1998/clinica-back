<?php

namespace App\Models;

use App\Models\Aseguradora;
use App\Models\Atencion;
use App\Models\CitaEstado;
use App\Models\Especialidad;
use App\Models\Medico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $table = 'Citas'; // Nombre de la tabla en la BD

    protected $fillable = [
        'empresa_id',
        'paciente_id',
        'aseguradora_id',
        'medico_id',
        'especialidad_id',
        'motivo_cita_id',
        'fecha',
        'hora',
        'estado_id'
    ];


    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function estado()
    {
        return $this->belongsTo(CitaEstado::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function atencion()
    {
        return $this->hasOne(Atencion::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function motivo()
    {
        return $this->belongsTo(MotivoCita::class,'motivo_cita_id');
    }


}
