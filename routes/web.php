<?php

use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;


Route::get('/auth/google', [UserAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserAuthController::class, 'handleGoogleCallback']);
//php artisan jwt:secret
Route::get('/', function () {
    return view('welcome');
});
//php artisan jwt:secret
// Ruta comodín para manejar las rutas de Vue.js
Route::get('/{any}', function () {
    return view('welcome'); // Asegúrate de que esta vista cargue tu compilado de Vue.js
})->where('any', '.*');
