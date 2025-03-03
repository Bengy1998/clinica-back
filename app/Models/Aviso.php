<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nro_vuelo',
        'linea_id',
        'eta_aprox',
        'hawg',
        'origen',
        'destino',
        'cliente_id',
        'proveedor_id',
        'contenido',
        'bultos',
        'peso',
        'mrn',
        'secuencial',
        'nro_anviso',
        'tipo_flete_term',
        'empresa_id',
        'tipo_aviso'
    ];


    // Valores predeterminados
    protected $attributes = [
        'tipo_aviso' => 1,
    ];

    // Agregar appends para incluir atributos virtuales en la serialización
    protected $appends = ['tipo_flete_term_nombre', 'tipo_aviso_nombre'];

    // Cargar automáticamente las relaciones
    protected $with = ['linea', 'cliente', 'proveedor'];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }

    // Relación con la tabla lineas_aereas
    public function linea()
    {
        return $this->belongsTo(LineaAerea::class, 'linea_id');
    }

    // Relación con la tabla clientes
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con la tabla proveedores
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    /**
     * Accesor para obtener el nombre del tipo de flete/term.
     */
    public function getTipoFleteTermNombreAttribute()
    {
        switch ($this->tipo_flete_term) {
            case 1:
                return 'Flete Collet';
            case 2:
                return 'Flete Prepaid';
            default:
                return '';
        }
    }

    public function getTipoAvisoNombreAttribute()
    {
        switch ($this->tipo_aviso) {
            case 1:
                return 'Pre Aviso';
            case 2:
                return 'Aviso';
            default:
                return '';
        }
    }
}
