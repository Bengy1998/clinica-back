<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aseguradora;
use App\Models\Cita;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Paciente;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    use ResponseTrait;

    /**
     * Obtener estadísticas del dashboard
     */
    public function estadisticas(Request $request)
    {
        try {
            $empresaId = $request->input('empresa_id');

            $estadisticas = [
                'total_medicos' => Medico::count(),
                'total_pacientes' => Paciente::when($empresaId, function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })->count(),
                'total_citas' => Cita::when($empresaId, function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })->count(),
                'total_aseguradoras' => Aseguradora::when($empresaId, function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })->count(),
                'total_especialidades' => Especialidad::count()
            ];

            return $this->responseJson($estadisticas);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtener citas del día actual
     */
    public function citasDelDia(Request $request)
    {
        try {
            $fechaHoy = now()->format('Y-m-d');
            $empresaId = $request->input('empresa_id');

            $citas = Cita::with(['paciente', 'motivo'])
                ->where('fecha', $fechaHoy)
                ->when($empresaId, function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->orderBy('hora', 'asc')
                ->get();

            $citasFormateadas = $citas->map(function ($cita) {
                return [
                    'id' => $cita->id,
                    'paciente' => $cita->paciente ? trim($cita->paciente->nombres . ' ' . $cita->paciente->apellido_paterno . ' ' . $cita->paciente->apellido_materno) : '',
                    'hora_inicio' => $cita->hora ? date('g:i A', strtotime($cita->hora)) : '',
                    'hora_fin' => $cita->hora_fin ? date('g:i A', strtotime($cita->hora_fin)) : '',
                    'motivo' => $cita->motivo ? $cita->motivo->descripcion : ''
                ];
            });

            return $this->responseJson([
                'fecha' => $fechaHoy,
                'total_citas' => $citasFormateadas->count(),
                'citas' => $citasFormateadas
            ]);
        } catch (\Throwable $th) {
            return $this->responseErrorJson($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
