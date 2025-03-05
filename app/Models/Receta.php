<?php

namespace App\Models;

use App\Models\Atencion;
use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'alergias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'medicamento_id',
        'atencion_id',
        'cantidad',
        'dosis',
        'instrucciones'
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function atencion()
    {
        return $this->belongsTo(Atencion::class);
    }
}
