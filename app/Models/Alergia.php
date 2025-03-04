<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alergia extends Model
{
    protected $table = 'alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre', // Nombre de la alergia
        'descripcion', // Descripción de la alergia
        'tipo', // Tipo de alergia (ej: alimento, medicamento, ambiental)
    ];
}
