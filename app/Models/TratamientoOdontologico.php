<?php

namespace App\Models;

use App\Models\Atencion;
use App\Models\Diente;
use App\Models\TratamientoTipo;
use Illuminate\Database\Eloquent\Model;

class TratamientoOdontologico extends Model
{
    protected $table = 'alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'atencion_id',
        'diente_id',
        'tratamiento_tipo_id',
        'costo',
        'estado_id'
    ];

    public function atencion()
    {
        return $this->belongsTo(Atencion::class);
    }

    public function diente()
    {
        return $this->belongsTo(Diente::class);
    }

    public function tratamientoTipo()
    {
        return $this->belongsTo(TratamientoTipo::class);
    }



}
