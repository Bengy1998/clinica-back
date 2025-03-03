<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory; 
    
    protected $table = 'planes';
    protected $fillable = ['nombre', 'precio', 'descripcion', 'tipo_plan'];

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_plan')
                    ->withPivot('fecha_inicio', 'fecha_fin', 'estado')
                    ->withTimestamps();
    }
}
