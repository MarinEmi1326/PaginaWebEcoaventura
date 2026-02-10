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

// apenas se agrego las vistas 
Route::get('/destinos', function () {
    return view('destinos.index');
})->name('destinos.index');

Route::get('/destinos/{tipo}', function ($tipo) {
    return view('destinos.index', compact('tipo'));
})
->whereIn('tipo', ['turisticos','ecoturisticos','balnearios'])
->name('destinos.tipo');

// apenas se va a agregar de las vistas 
Route::get('/servicios', function () {
    return view('servicios.index');
})->name('servicios.index');

Route::get('/servicios/{tipo}', function ($tipo) {
    return view('servicios.index', compact('tipo'));
})
->whereIn('tipo', ['hospedaje','restaurantes'])
->name('servicios.tipo');

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
        Route::get('/solicitudes', [AdminSolicitudesController::class, 'index'])
        ->name('solicitudes.index');

        Route::get('/solicitudes/crear', [AdminSolicitudesController::class, 'create'])->name('solicitudes.create');
        Route::post('/solicitudes/guardar', [AdminSolicitudesController::class, 'store'])->name('solicitudes.store');
        Route::get('/solicitudes/{id}/edit', [AdminSolicitudesController::class, 'edit'])->name('solicitudes.edit');
        Route::put('/solicitudes/{id}', [AdminSolicitudesController::class, 'update'])->name('solicitudes.update');

        Route::post('/solicitudes/{id}/toggle-activo', [AdminSolicitudesController::class, 'toggleActivo'])
        ->name('solicitudes.toggle');
        Route::get('/solicitudes/{id}', [AdminSolicitudesController::class, 'show'])
            ->name('solicitudes.show');

        Route::post('/solicitudes/{id}/aprobar', [AdminSolicitudesController::class, 'aprobar'])
            ->name('solicitudes.aprobar');

        Route::post('/solicitudes/{id}/rechazar', [AdminSolicitudesController::class, 'rechazar'])
            ->name('solicitudes.rechazar');

    });

    /*
    |--------------------------------------------------------------------------
    | HOTELERO
    |--------------------------------------------------------------------------
    */
    Route::prefix('hotelero')->name('hotelero.')->group(function () {

        Route::get('/dashboard', [HoteleroController::class, 'dashboard'])->name('index');
        Route::get('/reservas', [HoteleroController::class, 'reservas'])->name('reservas');
        Route::get('/habitaciones', [HoteleroController::class, 'habitaciones'])->name('habitaciones');
        Route::get('/servicios', [HoteleroController::class, 'servicios'])->name('servicios');
        Route::get('/mi-hotel', [HoteleroController::class, 'miHotel'])->name('mi-hotel');
        Route::post('/hotel/actualizar', [HoteleroController::class, 'update'])->name('hotel.update');
        
        Route::get('/reservas/crear', [HoteleroController::class, 'createReserva'])->name('reservas.create');
        Route::post('/reservas/guardar', [HoteleroController::class, 'storeReserva'])->name('reservas.store');
        Route::get('/hotel/editar', [HoteleroController::class, 'editHotel'])->name('hotel.edit');
        Route::post('/hotel/suspender', [HoteleroController::class, 'suspenderHotel'])->name('hotel.suspender');

        Route::get('/reservas/{id}', [HoteleroController::class, 'showReserva'])->name('reservas.show');
        Route::post('/reservas/{id}/aprobar', [HoteleroController::class, 'aprobarReserva'])->name('reservas.aprobar');
        Route::post('/reservas/{id}/rechazar', [HoteleroController::class, 'rechazarReserva'])->name('reservas.rechazar');
        // Perfil
        Route::get('/perfil', [HoteleroController::class, 'perfil'])->name('perfil');
        Route::put('/perfil', [HoteleroController::class, 'updatePerfil'])->name('perfil.update');
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
