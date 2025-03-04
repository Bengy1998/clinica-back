<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoOdontograma extends Model
{
    protected $table = 'estado_odontograma'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
