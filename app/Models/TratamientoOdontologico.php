<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TratamientoOdontologico extends Model
{
    protected $table = 'alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'atencion_id',
        'cliente_id',
        'tratamiento_tipo_id',
        'costo',
        'estado_id'
    ];
}
