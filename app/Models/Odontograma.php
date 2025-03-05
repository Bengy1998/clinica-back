<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odontograma extends Model
{
    protected $table = 'odontograma'; // Nombre de la tabla en la BD

    protected $fillable = [
        'paciente_id',
        'diente_id',
        'estado_id',
        'observaciones'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function estado()
    {
        return $this->belongsTo(EstadoOdontograma::class);
    }

    public function diente()
    {
        return $this->belongsTo(Diente::class);
    }
}
