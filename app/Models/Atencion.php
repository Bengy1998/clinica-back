<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    protected $table = 'atenciones'; // Nombre de la tabla en la BD

    protected $fillable = [
        'cita_id',
        'fecha_atencion',
        'hora_atencion',
        'diagnostico_id',
        'tratamiento',
        'observaciones',
        'created_at'
    ];
}
