<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionDiagnostico extends Model
{
    protected $table = 'atenciones_diagnosticos'; // Nombre de la tabla en la BD

    protected $fillable = [
        'atencion_id',
        'diagnostico_id'
    ];
}
