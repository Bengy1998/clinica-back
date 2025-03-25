<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoIdentidad extends Model
{
    protected $table = 'tipo_documento_identidad'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre',
        'codigo_corto',
        'estado'
    ];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
