<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDestinoController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiAdminDestinosController;
use App\Http\Controllers\Api\ApiRutaController;


// Públicas - sin token
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/registro/turista', [ApiAuthController::class, 'registroTurista']);
Route::get('/destinos', [ApiDestinoController::class, 'index']);
Route::get('/destinos/{id}', [ApiDestinoController::class, 'show']);
Route::get('/destinos/{id}/comentarios', [ApiDestinoController::class, 'comentarios']);
Route::get('/destinos/categoria/{id_categoria}', [ApiDestinoController::class, 'porCategoria']);



Route::get('/rutas', [ApiRutaController::class, 'index']);
Route::get('/rutas/{id}', [ApiRutaController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/perfil', [ApiAuthController::class, 'perfil']);
    Route::post('/destinos/{id}/comentar', [ApiDestinoController::class, 'comentar']);
    Route::post('/destinos/{id}/reportar', [ApiDestinoController::class, 'reportar']);
    Route::post('/destinos', [ApiDestinoController::class, 'store']);

    Route::get('/favoritos', [ApiDestinoController::class, 'favoritos']);
    Route::post('/favoritos/{id}/toggle', [ApiDestinoController::class, 'toggleFavorito']);

    Route::put('/perfil', [ApiAuthController::class, 'actualizarPerfil']);

    Route::delete('/perfil', [ApiAuthController::class, 'eliminarCuenta']);



    
    Route::get('/admin/dashboard', [ApiAdminDestinosController::class, 'dashboard']);
    Route::get('/admin/destinos', [ApiAdminDestinosController::class, 'misDestinos']);
    Route::get('/admin/pagos', [ApiAdminDestinosController::class, 'pagos']);

    Route::get('/turista/pagos', [ApiAuthController::class, 'misPagos']);
});
