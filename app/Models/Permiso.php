<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'modulo_id'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permiso_rol', 'permiso_id', 'role_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
