<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TratamientoTipo extends Model
{

    protected $table = 'tratamientos_odontologicos'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
