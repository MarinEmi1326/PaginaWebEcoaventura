<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSitioController;
use App\Http\Controllers\HoteleroController;
use App\Http\Controllers\RestauranteroController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Admin\AdminSolicitudesController;


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('home'))->name('home');


Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


/*
|--------------------------------------------------------------------------
|  RUTAS PROTEGIDAS (UN SOLO MIDDLEWARE)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Perfil (común)
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::post('/perfil', [PerfilController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        //  UNA SOLA LÍNEA: CRUD COMPLETO
        Route::resource('sitios', AdminSitioController::class);
        Route::get('/solicitudes', [AdminSolicitudesController::class, 'index'])->name('solicitudes.index');
        Route::get('solicitudes/{id}', [AdminSolicitudesController::class, 'show'])->name('solicitudes.show');

    });

    /*
    |--------------------------------------------------------------------------
    | HOTELERO
    |--------------------------------------------------------------------------
    */
    Route::prefix('hotelero')->name('hotelero.')->group(function () {

        Route::get('/dashboard', [HoteleroController::class, 'dashboard'])->name('dashboard');
        Route::get('/reservas', [HoteleroController::class, 'reservas'])->name('reservas');
        Route::get('/habitaciones', [HoteleroController::class, 'habitaciones'])->name('habitaciones');
        Route::get('/servicios', [HoteleroController::class, 'servicios'])->name('servicios');
    });

    /*
    |--------------------------------------------------------------------------
    | RESTAURANTERO
    |--------------------------------------------------------------------------
    */
    Route::prefix('restaurantero')->name('restaurantero.')->group(function () {

        Route::get('/dashboard', [RestauranteroController::class, 'dashboard'])->name('dashboard');
        Route::get('/menu', [RestauranteroController::class, 'menu'])->name('menu');
        Route::get('/reservas', [RestauranteroController::class, 'reservas'])->name('reservas');
        Route::get('/opiniones', [RestauranteroController::class, 'opiniones'])->name('opiniones');
    });

});
