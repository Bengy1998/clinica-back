<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoCita extends Model
{
    protected $table = 'motivo_cita'; // Nombre de la tabla en la BD

    protected $fillable = [
        'descripcion'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
