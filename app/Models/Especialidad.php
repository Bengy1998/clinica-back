<?php

namespace App\Models;

use App\Models\Cita;
use App\Models\Medico;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'estado'
    ];


    public function atencion()
    {
        return $this->belongsToMany(Medico::class, 'medicos_especialidades', 'especialidad_id', 'atencion_id');
    }

    public function cita()
    {
        return $this->hasMany(Cita::class);
    }

    public function medico()
    {
        return $this->belongsToMany(Medico::class);
    }
}
