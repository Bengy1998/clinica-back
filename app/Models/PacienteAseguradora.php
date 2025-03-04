<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacienteAseguradora extends Model
{
    protected $table = 'alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'paciente_id',
        'aseguradora_id',
        'numero_poliza'

    ];



}
