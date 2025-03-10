<?php

use App\Http\Controllers\Api\AseguradoraController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;




Route::get('/auth/google', [UserAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserAuthController::class, 'handleGoogleCallback']);
// Asegurarse de que la ruta login no se vea afectada por middleware que intente parsear el token
Route::middleware('verify.domain')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
});

// Las demás rutas requieren tanto 'verify.domain' como el middleware 'api' (que maneja la verificación del token)
Route::middleware(['verify.domain', 'jwt'])->group(function () {
    Route::post('logout', [UserAuthController::class, 'logout']);

    Route::controller(EmpresaController::class)->prefix('empresas')->group(function () {
        Route::get('/', 'index')->name('ver-empresa');
        Route::post('/', 'store')->name('crear-empresa');
        Route::get('/{id}', 'show')->name('ver-empresa');
        Route::put('/{id}', 'update')->name('editar-empresa');
    });
});



// Demo Controllador

/*
Route::get('/aseguradoras', [AseguradoraController::class, 'index']);
Route::post('/aseguradoras', [AseguradoraController::class, 'store']);
Route::put('/aseguradoras/{id}', [AseguradoraController::class, 'update']);
*/


// Rutas para aseguradoras

Route::controller(AseguradoraController::class)->prefix('aseguradoras')->group(function () {
    Route::get('/', 'index')->name('aseguradoras.index');
    Route::post('/', 'store')->name('aseguradoras.store');
    Route::get('/{aseguradora}', 'show')->name('aseguradoras.show');
    Route::put('/{aseguradora}', 'update')->name('aseguradoras.update');
    Route::delete('/{aseguradora}', 'destroy')->name('aseguradoras.destroy'); // Ruta para eliminar
});


Route::controller(PacienteController::class)->prefix('pacientes')->group(function () {
    Route::get('/', 'index')->name('pacientes.index');
    Route::post('/', 'store')->name('pacientes.store');
    Route::get('/{paciente}', 'show')->name('pacientes.show');
    Route::put('/{paciente}', 'update')->name('pacientes.update');
    Route::delete('/{paciente}', 'destroy')->name('pacientes.destroy'); // Ruta para eliminar
});


Route::get('/x', function () {
    return response()->json(['message' => 'bjar es mi mujer']);
});


