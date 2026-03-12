<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDestinoController;
use App\Http\Controllers\Api\ApiAuthController;

// Auth
Route::post('/login', [ApiAuthController::class, 'login']);

// Destinos (requieren token)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/destinos', [ApiDestinoController::class, 'store']);
    Route::post('/destinos/{id}/comentar', [ApiDestinoController::class, 'comentar']);
    Route::get('/destinos',                         [ApiDestinoController::class, 'index']);
    Route::get('/destinos/{id}',                    [ApiDestinoController::class, 'show']);
    Route::get('/destinos/categoria/{id_categoria}', [ApiDestinoController::class, 'porCategoria']);
    Route::get('/destinos/{id}/comentarios',        [ApiDestinoController::class, 'comentarios']);
});
