<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{


    protected $table = 'modulos';

    protected $fillable = ['nombre', 'descripcion'];

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_modulo');
    }
}
