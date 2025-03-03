<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'ruc', 'correo', 'telefono', 'dominio', 'estado'];

    public function users()
    {
        return $this->hasMany(User::class, 'empresa_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function planes()
    {
        return $this->belongsToMany(Plan::class, 'empresa_plan')
            ->withPivot('fecha_inicio', 'fecha_fin', 'estado')
            ->withTimestamps();
    }
}
