<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionAlergia extends Model
{
    protected $table = 'atenciones_alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'atencion_id',
        'alergia_id'
    ];
}
