<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSitioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DestinosController;
use App\Http\Controllers\Admin\AdminSolicitudesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('home'))->name('home');

Route::view('/cultura', 'cultura')->name('cultura');

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// REGISTRO TURISTA
Route::get('/registro', [AuthController::class, 'showRegistroTurista'])->name('register');
Route::post('/registro', [AuthController::class, 'registroTurista'])->name('register.post');

// REGISTRO ADMIN DESTINOS (footer)
Route::get('/registro/destinos', [AuthController::class, 'showRegistroDestinos'])->name('registro.destinos');
Route::post('/registro/destinos', [AuthController::class, 'registroDestinos']);

// REGISTRO GESTOR RUTAS (footer)
Route::get('/registro/rutas', [AuthController::class, 'showRegistroRutas'])->name('registro.rutas');
Route::post('/registro/rutas', [AuthController::class, 'registroRutas']);

// GOOGLE LOGIN
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/centros', [DestinosController::class, 'index'])->name('destinos.index');
Route::get('/centros/{tipo}', [DestinosController::class, 'tipo'])
    ->whereIn('tipo', ['turisticos', 'ecoturisticos', 'balnearios'])
    ->name('destinos.tipo');
Route::get('/centros/{id}', [DestinosController::class, 'show'])->name('destinos.show');

// OTRAS VISTAS
Route::get('/mapa', fn () => view('mapa'))->name('mapa');
Route::get('/turismo-responsable', fn () => view('turismo-responsable'))->name('turismo-responsable');
Route::get('/ruta', fn () => view('ruta'))->name('ruta');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::post('/perfil', [PerfilController::class, 'update']);

    // Dashboards
    Route::get('/turista/dashboard', fn () => view('turista.dashboard'))->name('turista.dashboard');
    Route::get('/destinos/dashboard', fn () => view('destinos.dashboard'))->name('destinos.dashboard');
    Route::get('/rutas/dashboard', fn () => view('rutas.dashboard'))->name('rutas.dashboard');

    /*
    |--------------------------------------------------------------------------
    | PANEL ADMIN DESTINOS (NUEVO)
    |--------------------------------------------------------------------------
    */
    Route::get('/mis-destinos', function () {
        return view('admin.destinos.index');
    })->name('misdestinos.index');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/index', [AdminDashboardController::class, 'index'])->name('index');

        Route::get('/destinos', function () {
            return view('admin.destinos.index');
        })->name('destinos');

        Route::get('/aprobacion', fn () => view('admin.aprobacion'))->name('aprobacion');
        Route::get('/reportes', fn () => view('admin.reportes'))->name('reportes');
        Route::get('/respaldos', fn () => view('admin.respaldos'))->name('respaldos');

        Route::resource('sitios', AdminSitioController::class);

        Route::get('/solicitudes', [AdminSolicitudesController::class, 'index'])->name('solicitudes.index');
        Route::get('/solicitudes/crear', [AdminSolicitudesController::class, 'create'])->name('solicitudes.create');
        Route::post('/solicitudes/guardar', [AdminSolicitudesController::class, 'store'])->name('solicitudes.store');
        Route::get('/solicitudes/{id}/edit', [AdminSolicitudesController::class, 'edit'])->name('solicitudes.edit');
        Route::put('/solicitudes/{id}', [AdminSolicitudesController::class, 'update'])->name('solicitudes.update');
        Route::post('/solicitudes/{id}/toggle-activo', [AdminSolicitudesController::class, 'toggleActivo'])->name('solicitudes.toggle');
        Route::get('/solicitudes/{id}', [AdminSolicitudesController::class, 'show'])->name('solicitudes.show');
        Route::post('/solicitudes/{id}/aprobar', [AdminSolicitudesController::class, 'aprobar'])->name('solicitudes.aprobar');
        Route::post('/solicitudes/{id}/rechazar', [AdminSolicitudesController::class, 'rechazar'])->name('solicitudes.rechazar');
    });

});