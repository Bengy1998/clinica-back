<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes'; // Nombre de la tabla en la BD

    protected $fillable = [
        'empresa_id',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'tipo_documento_id',
        'numero_documento_identidad',
        'telefono',
        'email',
        'fecha_nacimiento'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumentoIdentidad::class, 'tipo_documento_id');
    }
}
