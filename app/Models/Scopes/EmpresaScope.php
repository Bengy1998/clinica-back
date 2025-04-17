<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    /**
     * Aplicar el scope a una consulta Eloquent.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Priorizar el empresa_id del request, y como respaldo usar el del usuario autenticado
        $empresa_id = request()->input('empresa_id') ?? auth()->user()?->empresa_id;

        // Aplicar el filtro solo si empresa_id está disponible
        if ($empresa_id) {
            $builder->where('empresa_id', $empresa_id);
        }
    }
}
