<?php

use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\EmpresaController;
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
