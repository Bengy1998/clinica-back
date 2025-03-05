<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicoEspecialidad extends Model
{
    protected $table = 'medicos_especialidades'; // Nombre de la tabla en la BD

    protected $fillable = [
        'medico_id',
        'especialidad_id'
    ];

}
