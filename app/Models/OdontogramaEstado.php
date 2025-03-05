<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontogramaEstado extends Model
{
    protected $table = 'odontograma_estado'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function odontogramas()
    {
        return $this->hasMany(Odontograma::class);
    }
}
