<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $fillable = ['nombre', 'valor', 'por_defecto'];
}
