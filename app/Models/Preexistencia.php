<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preexistencia extends Model
{
    protected $table = 'preexistencias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
