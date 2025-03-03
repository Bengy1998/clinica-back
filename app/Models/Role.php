<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'nombre',
        'empresa_id',
    ];

    // Relación con la tabla empresas
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_rol');
    }
}
