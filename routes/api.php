<?php

use App\Http\Controllers\Api\AseguradoraController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;


// Asegurarse de que la ruta login no se vea afectada por middleware que intente parsear el token
Route::middleware('verify.domain')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
});

Route::middleware('verify.domain')->post('logout', [UserAuthController::class, 'logout']);
// Las demás rutas requieren tanto 'verify.domain' como el middleware 'api' (que maneja la verificación del token)
Route::middleware(['verify.domain', 'jwt'])->group(function () {

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




});
// Ruta para obtener los motivos de cita fuera del grupo 'citas'
Route::get('/motivos', [CitaController::class, 'MotivoCita'])->name('motivo-cita');
Route::get('/estados', [CitaController::class, 'EstadoCita'])->name('estado-cita');



Route::get('/x', function () {
    return response()->json(['message' => 'bjar es mi mujer']);
});
