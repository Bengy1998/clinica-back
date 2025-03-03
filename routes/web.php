<?php

use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;


Route::get('/auth/google', [UserAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserAuthController::class, 'handleGoogleCallback']);
//php artisan jwt:secret