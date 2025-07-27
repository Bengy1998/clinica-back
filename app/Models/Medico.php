<?php

namespace App\Models;

use App\Models\Especialidad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $table = 'medico'; // Nombre de la tabla en la BD
    use HasFactory; //  Agregar esto
    
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'tipo_documento_identidad_id',
        'numero_documento_identidad',
        'usuario_id'
    ];

    /**
     * Atributos que deben ser añadidos a la representación del array/JSON del modelo.
     */
    protected $appends = ['nombre_completo'];

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'medico_especialidad', 'medico_id', 'especialidad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tipo_documento(){
        return $this->belongsTo(TipoDocumentoIdentidad::class, 'tipo_documento_identidad_id');
    }

    /**
     * Accessor para obtener el nombre completo del médico
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }

}
