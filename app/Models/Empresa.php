<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'ruc', 'email', 'telefono', 'estado'];


}
