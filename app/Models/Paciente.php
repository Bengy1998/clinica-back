<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;
    protected $table = 'pacientes';

    protected $fillable = [
        'empresa_id',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'numero_documento_identidad',
        'tipo_documento_identidad_id',
        'telefono',
        'email',
        'fecha_nacimiento'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumentoIdentidad::class, 'tipo_documento_identidad_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
