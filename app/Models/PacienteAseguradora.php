<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacienteAseguradora extends Model
{
    protected $table = 'pacientes_aseguradoras'; // Nombre de la tabla en la BD

    protected $fillable = [
        'paciente_id',
        'aseguradora_id',
        'numero_poliza'

    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class);
    }






}
