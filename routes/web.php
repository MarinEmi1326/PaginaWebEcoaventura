<?php

use App\Http\Controllers\Admin\AdminCentroController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminReportesController;
use App\Http\Controllers\Admin\AdminSolicitudesController;
use App\Http\Controllers\AdminDestinoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\DestinosController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RutaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::resource('rutas', RutaController::class)->only(['index', 'create', 'store']);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/cultura', 'cultura')->name('cultura');

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// REGISTRO TURISTA
Route::get('/registro/turista', [AuthController::class, 'showRegistroTurista'])->name('registro.turista');
Route::post('/registro/turista', [AuthController::class, 'registroTurista'])->name('registro.turista.post');

// REGISTRO ADMIN DESTINOS
Route::get('/registro/destinos', [AuthController::class, 'showRegistroDestinos'])->name('registro.destinos');
Route::post('/registro/destinos', [AuthController::class, 'registroDestinos'])->name('registro.destinos.post');

// VISTA DE ÉXITO DEL REGISTRO
Route::get('/registro/destinos/exito', function () {
    return view('auth.registro-destinos-exito');
})->name('registro.destinos.exito');

// REGISTRO GESTOR RUTAS
Route::get('/registro/rutas', [AuthController::class, 'showRegistroRutas'])->name('registro.rutas');
Route::post('/registro/rutas', [AuthController::class, 'registroRutas']);

// VERIFICAR CORREO
Route::get('/verificar-correo/{token}', [AuthController::class, 'verificarCorreo'])->name('verificar.correo');

// GOOGLE LOGIN
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/centros', [DestinosController::class, 'index'])->name('destinos.index');
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
    // PERFIL SEGÚN ROL
    Route::get('/perfil', function () {
        $rol = Auth::user()->rol;

        return match ($rol) {
            'turista' => view('perfil.show-turista'),
            'gestor_rutas' => view('perfil.show-rutas'),
            'admin_destinos' => view('perfil.show-destinos'),
            'admin_general' => view('perfil.show-admin'),
            default => abort(403, 'Rol no reconocido'),
        };
    })->name('perfil');

    // ACCIÓN DE ACTUALIZAR (Agrégala aquí abajo)
    Route::put('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');

    // reporte
    Route::post('/destinos/{id}/reportar', [ReporteController::class, 'reportarDestino'])->name('reportes.destino');
    Route::post('/comentarios/{id}/reportar', [ReporteController::class, 'reportarComentario'])->name('reportes.comentario');

    // PANEL ADMIN DESTINOS
    Route::get('/mis-destinos', [AdminDestinoController::class, 'index'])->name('misdestinos.index');
    Route::get('/destinos/crear', [AdminDestinoController::class, 'create'])->name('destinos.create');
    Route::post('/destinos/crear', [AdminDestinoController::class, 'store'])->name('destinos.store');
    Route::delete('/destinos/imagen/{id}', [AdminDestinoController::class, 'destroyImagen'])->name('destinos.imagen.destroy');
    Route::get('/destinos/{id}/editar', [AdminDestinoController::class, 'edit'])->name('destinos.edit');
    Route::put('/destinos/{id}', [AdminDestinoController::class, 'update'])->name('destinos.update');
    Route::delete('/destinos/{id}', [AdminDestinoController::class, 'destroy'])->name('destinos.destroy');

    // Comentarios
    Route::post('/centros/{id}/comentar', [ComentarioController::class, 'storeDestino'])->name('comentarios.destino.store');
    Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    // pagos
    Route::get('/paquetes/{id}/pagar', [PagoController::class, 'show'])->name('pagos.show');
    Route::post('/paquetes/{id}/pagar', [PagoController::class, 'procesar'])->name('pagos.procesar');
    Route::get('/paquetes/{id}/confirmacion', [PagoController::class, 'confirmacion'])->name('pagos.confirmacion');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/index', [AdminDashboardController::class, 'index'])->name('index');

        // panel admin general , para que le aparesca los destinos reportados
        Route::get('/destino', [AdminCentroController::class, 'index'])->name('destino');
        Route::post('/destino/{id}/toggle', [AdminCentroController::class, 'toggleActivo'])->name('destino.toggle');
        Route::get('/respaldos', fn () => view('admin.respaldos'))->name('respaldos');

        // Reportes
        Route::get('/reportes', [AdminReportesController::class, 'index'])->name('reportes');
        Route::get('/reportes/destino/{id}', [AdminReportesController::class, 'showDestino'])->name('reportes.showDestino');
        Route::post('/reportes/{id}/resolver', [AdminReportesController::class, 'resolver'])->name('reportes.resolver');
        Route::post('/reportes/{id}/rechazar', [AdminReportesController::class, 'rechazar'])->name('reportes.rechazar');
        Route::post('/reportes/comentario/{id}/eliminar', [AdminReportesController::class, 'eliminarComentario'])->name('reportes.comentario.eliminar');

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

    Route::middleware(['auth'])->group(function () {

        // Ruta principal para ver el perfil y actualizarlo
        Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
        Route::put('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');

        // SOLUCIÓN TEMPORAL AL ERROR:
        // Como tu layout (app.blade.php) ya busca estas rutas pero aún no tienes los dashboards,
        // las creamos apuntando al perfil para que la web no se rompa.
        Route::get('/admin/dashboard', [PerfilController::class, 'show'])->name('admin.index');
        Route::get('/destinos/dashboard', [PerfilController::class, 'show'])->name('destinos.index');
        Route::get('/rutas/dashboard', [PerfilController::class, 'show'])->name('rutas.dashboard');
    });
});
