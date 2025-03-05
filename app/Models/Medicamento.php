<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $table = 'Medicamentos'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'descripcion',
        'presentacion',
        'contraindicaciones'
        ];

    public function recetas(){
        return $this->belongsToMany(Receta::class);
    }

}
