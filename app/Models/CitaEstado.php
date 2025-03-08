<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitaEstado extends Model
{
     /** @use HasFactory<\Database\Factories\CitaFactory> */


     protected $table = 'cita_estado'; // Nombre de la tabla en la BD

     protected $fillable = [
         'nombre',
         'estado'
     ];

     public function cita()
    {
        return $this->belongsToMany(Cita::class);
    }

}
