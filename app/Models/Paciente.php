<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
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

    protected $appends = ['nombre_completo'];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Cargar automáticamente las relaciones
    protected $with = ['tipo_documento'];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }

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

    // Accessor para el nombre completo
    public function getNombreCompletoAttribute()
    {
        $nombres = trim($this->nombres);
        $apellidoPaterno = trim($this->apellido_paterno ?? '');
        $apellidoMaterno = trim($this->apellido_materno ?? '');

        return trim("{$nombres} {$apellidoPaterno} {$apellidoMaterno}");
    }

    // Accessor para formatear la fecha de nacimiento
    public function getFechaNacimientoAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    // Mutators para limpiar espacios al guardar
    public function setNombresAttribute($value)
    {
        $this->attributes['nombres'] = trim($value);
    }

    public function setApellidoPaternoAttribute($value)
    {
        $this->attributes['apellido_paterno'] = trim($value);
    }

    public function setApellidoMaternoAttribute($value)
    {
        $this->attributes['apellido_materno'] = trim($value);
    }
}
