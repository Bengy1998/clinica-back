<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoIdentidad extends Model
{
    protected $table = 'alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'codigo_corto',
        'estadp'
    ];
}
