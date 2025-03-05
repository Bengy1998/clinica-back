<?php

namespace App\Models;

use App\Models\Alergia;
use App\Models\Cita;
use App\Models\Diagnostico;
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
        'observaciones'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function diagnosticos()
    {
        return $this->belongsToMany(Diagnostico::class, 'atencion_diagnostico');
    }

    public function alergias()
    {
        return $this->belongsToMany(Alergia::class, 'atenciones_alergias', 'atencion_id', 'alergia_id');
    }


}
