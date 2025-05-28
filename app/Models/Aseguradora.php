<?php

namespace App\Models;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Aseguradora extends Model
{
    use HasFactory; //  Agregar esto
    protected $table = 'aseguradoras'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'ruc',
        'telefono', // Tipo de alergia (ej: alimento, medicamento, ambiental)
        'email',
        'empresa_id',
        'created_at',
        'updated_at'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
