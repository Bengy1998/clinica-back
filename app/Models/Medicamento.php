<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $table = 'Medicamentos'; // Nombre de la tabla en la BD
    use HasFactory; //  Agregar esto
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
