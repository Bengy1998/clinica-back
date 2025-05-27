<?php

use App\Http\Controllers\Api\AseguradoraController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\EspecialidadController;
use App\Http\Controllers\Api\EstadoCitaController;
use App\Http\Controllers\Api\MotivoCitaController;
use App\Http\Controllers\Api\EspecialidadController;
use App\Http\Controllers\Api\EstadoCitaController;
use App\Http\Controllers\Api\MotivoCitaController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\TipoDocumentoIdentidadController;
use App\Http\Controllers\Api\TipoDocumentoIdentidadController;
use App\Http\Controllers\Api\UserAuthController;
use App\Models\Especialidad;
use App\Models\TipoDocumentoIdentidad;
use App\Models\Especialidad;
use App\Models\TipoDocumentoIdentidad;
use Illuminate\Support\Facades\Route;








// Asegurarse de que la ruta login no se vea afectada por middleware que intente parsear el token
Route::middleware('verify.domain')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
});

// La ruta logout no pasa por el middleware de permisos
// Las demás rutas requieren tanto 'verify.domain' como el middleware 'api' (que maneja la verificación del token)
Route::middleware(['verify.domain', 'jwt'])->group(function () {

    Route::middleware('verify.domain')->post('logout', [UserAuthController::class, 'logout'])->name('logout');

    Route::controller(EmpresaController::class)->prefix('empresas')->group(function () {
        Route::get('/', 'index')->name('ver-empresa');
        Route::post('/', 'store')->name('crear-empresa');
        Route::get('/{id}', 'show')->name('ver-empresa');
        Route::put('/{id}', 'update')->name('editar-empresa');
        Route::delete('/{id}', 'destroy')->name('eliminar-empresa'); // Ruta para eliminar
    });

    Route::controller(AseguradoraController::class)->prefix('aseguradoras')->group(function () {
        Route::get('/', 'index')->name('ver-aseguradoras');
        Route::post('/', 'store')->name('crear-aseguradoras');
        Route::get('/{aseguradora}', 'show')->name('ver-aseguradoras');
        Route::put('/{aseguradora}', 'update')->name('editar-aseguradoras');
        Route::delete('/{aseguradora}', 'destroy')->name('eliminar-aseguradoras'); // Ruta para eliminar
    });

    Route::controller(PacienteController::class)->prefix('pacientes')->group(function () {
        Route::get('/', 'index')->name('ver-pacientes');
        Route::post('/', 'store')->name('crear-pacientes');
        Route::get('/{paciente}', 'show')->name('ver-pacientes');
        Route::put('/{paciente}', 'update')->name('editar-pacientes');
        Route::delete('/{paciente}', 'destroy')->name('eliminar-pacientes'); // Ruta para eliminar
    });

    Route::controller(CitaController::class)->prefix('citas')->group(function () {
        Route::get('/', 'index')->name('ver-citas');
        Route::post('/', 'store')->name('crear-citas');
        Route::get('/{cita}', 'show')->name('ver-citas');
        Route::put('/{cita}', 'update')->name('editar-citas');
        Route::delete('/{cita}', 'destroy')->name('eliminar-citas'); // Ruta para eliminar

    });

    Route::controller(TipoDocumentoIdentidadController::class)->prefix('tipo-documento-identidad')->group(function () {
        Route::get('/', 'index')->name('tipo-documento-identidad.select');
    });

    // Ruta para obtener los motivos de cita fuera del grupo 'citas'
    Route::get('/motivos', [CitaController::class, 'MotivoCita'])->name('motivo-cita');
    Route::get('/estados', [CitaController::class, 'EstadoCita'])->name('estado-cita');

});
// Ruta para obtener los motivos de cita fuera del grupo 'citas'
Route::get('/motivos', [MotivoCitaController::class, 'MotivoCita'])->name('motivo-cita');
Route::get('/estados', [EstadoCitaController::class, 'EstadoCita'])->name('estado-cita');
Route::get('/tipoDocumento', [TipoDocumentoIdentidadController::class, 'TipoDocumento'])->name('tipo-documento');
Route::get('/especialidades', [EspecialidadController::class, 'getEspecialidad'])->name('especialidad');
Route::get('/motivos', [MotivoCitaController::class, 'MotivoCita'])->name('motivo-cita');
Route::get('/estados', [EstadoCitaController::class, 'EstadoCita'])->name('estado-cita');
Route::get('/tipoDocumento', [TipoDocumentoIdentidadController::class, 'TipoDocumento'])->name('tipo-documento');
Route::get('/especialidades', [EspecialidadController::class, 'getEspecialidad'])->name('especialidad');


Route::get('/x', function () {
    return response()->json(['message' => 'bjar es mi mujer']);
});
