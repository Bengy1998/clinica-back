<?php

namespace App\Models;

use App\Models\Aseguradora;
use App\Models\Atencion;
use App\Models\CitaEstado;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'empresa_id',
        'paciente_id',
        'aseguradora_id',
        'medico_id',
        'especialidad_id',
        'motivo_cita_id',
        'fecha',
        'hora',
        'detalle',
        'hora_fin',
        'estado_id'
    ];

    /**
     * Valores por defecto para los atributos del modelo
     */
    protected $attributes = [
        'estado_id' => 1,
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }


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
