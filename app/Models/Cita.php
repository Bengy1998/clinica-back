<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $table = 'Citas'; // Nombre de la tabla en la BD

    protected $fillable = [
        'empresa_id',
        'paciente_id',
        'aseguradora_id',
        'medico_id',
        'especialidad_id',
        'fecha',
        'hora',
        'estado_id'
    ];



}
