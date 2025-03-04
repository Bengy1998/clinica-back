<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aseguradora extends Model
{
    protected $table = 'aseguradoras'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre', // Nombre de la alergia
        'ruc', // Descripción de la alergia
        'telefono', // Tipo de alergia (ej: alimento, medicamento, ambiental)
        'email',
        'empresa_id',
        'created_at',
    ];
}
