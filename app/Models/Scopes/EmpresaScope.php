<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Log;

class EmpresaScope implements Scope
{
    /**
     * Aplicar el scope a una consulta Eloquent.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Verificar si el request está disponible
        if (!request()) {
            return;
        }
        // Obtener el empresa_id del request
        $empresa_id = request()->input('empresa_id');

        if ($empresa_id) {
            $builder->where('empresa_id', $empresa_id);
        }
    }
}
