<?php

namespace App\Models;

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
}
