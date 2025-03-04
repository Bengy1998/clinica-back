<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diente extends Model
{
    protected $table = 'dientes'; // Nombre de la tabla en la BD

    protected $fillable = [
        'numero_fdi',
        'nombre'
    ];
}
