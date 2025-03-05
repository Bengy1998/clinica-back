<?php

namespace App\Models;

use App\Models\Atencion;
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $table = 'diagnosticos'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre', // Nombre de la alergia
        'estado', // Descripción de la alergia
        'cie10', // Tipo de alergia (ej: alimento, medicamento, ambiental)
    ];

    public function atenciones()
    {
        return $this->belongsToMany(Atencion::class, 'atencion_diagnostico');
    }

}
