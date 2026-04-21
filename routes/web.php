<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminCentroController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminReportesController;
use App\Http\Controllers\Admin\AdminSolicitudesController;
use App\Http\Controllers\Admin\AdminCategoriaController;
use App\Http\Controllers\AdminDestinoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDestinoReporteController;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\DestinosController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\Admin\ActividadController;
use App\Http\Controllers\Admin\RecomendacionController;
use App\Http\Controllers\Admin\AdminRespaldosController;
use App\Http\Controllers\Admin\AdminVentasController;
use App\Http\Controllers\TuristaViajesController;
use App\Http\Controllers\Admin\AdminUsuarioReporteController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

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
Route::get('/registro/destinos/exito', function () {
    return view('auth.registro-destinos-exito');
})->name('registro.destinos.exito');

// REGISTRO GESTOR RUTAS
Route::get('/registro/rutas', [AuthController::class, 'showRegistroRutas'])->name('registro.rutas.form');
Route::post('/registro/rutas', [AuthController::class, 'registroRutas'])->name('registro.rutas');
Route::get('/registro/rutas/exito', function () {
    return view('auth.registro-rutas-exito');
})->name('registro.rutas.exito');

// VERIFICAR CORREO
Route::get('/verificar-correo/{token}', [AuthController::class, 'verificarCorreo'])->name('verificar.correo');

// GOOGLE LOGIN
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/centros', [DestinosController::class, 'index'])->name('destinos.index');
Route::get('/centros/{id}', [DestinosController::class, 'show'])->name('destinos.show');

// OTRAS VISTAS
Route::get('/mapa', fn() => view('mapa'))->name('mapa');
Route::get('/turismo-responsable', fn() => view('turismo-responsable'))->name('turismo-responsable');

// Rutas públicas del módulo de rutas
Route::get('/ruta', [RutaController::class, 'publicIndex'])->name('ruta');
Route::get('/ruta/{id}', [RutaController::class, 'show'])->name('rutas.show');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // PERFIL
    Route::get('/perfil', function () {
        $user = Auth::user();
        $persona = $user->persona;
        $roles = $persona?->roles->pluck('descripcion')->toArray() ?? [];

        if (in_array('admin_general', $roles)) {
            return view('perfil.show-admin');
        }
        if (in_array('admin_destinos', $roles)) {
            return view('perfil.show-destinos');
        }
        if (in_array('gestor_rutas', $roles)) {
            return view('perfil.show-rutas');
        }
        if (in_array('turista', $roles)) {
            return view('perfil.show-turista');
        }

        abort(403, 'Rol no reconocido');
    })->name('perfil');

    Route::put('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');

    // Módulo de rutas turísticas
    Route::resource('rutas', RutaController::class)->only(['index', 'create', 'store', 'destroy', 'edit', 'update']);
    Route::get('/api/destino-info/{id}', [RutaController::class, 'infoDestino'])->name('api.destino.info');
    Route::delete('/rutas/imagen/{id}', [RutaController::class, 'destroyImagen'])->name('rutas.imagen.destroy');

    // Reportes
    Route::post('/destinos/{id}/reportar', [ReporteController::class, 'reportarDestino'])->name('reportes.destino');
    Route::post('/comentarios/{id}/reportar', [ReporteController::class, 'reportarComentario'])->name('reportes.comentario');
    Route::post('/ruta/{id}/comentar', [ComentarioController::class, 'storeRuta'])->name('comentarios.ruta.store');



    // PANEL ADMIN DESTINOS
    Route::get('/mis-destinos', [AdminDestinoController::class, 'index'])->name('misdestinos.index');
    Route::get('/destinos/crear', [AdminDestinoController::class, 'create'])->name('destinos.create');
    Route::post('/destinos/crear', [AdminDestinoController::class, 'store'])->name('destinos.store');
    Route::delete('/destinos/imagen/{id}', [AdminDestinoController::class, 'destroyImagen'])->name('destinos.imagen.destroy');
    Route::get('/destinos/{id}/editar', [AdminDestinoController::class, 'edit'])->name('destinos.edit');
    Route::put('/destinos/{id}', [AdminDestinoController::class, 'update'])->name('destinos.update');
    Route::delete('/destinos/{id}', [AdminDestinoController::class, 'destroy'])->name('destinos.destroy');
    Route::post('/destinos/{id}/toggle-activo', [AdminDestinoController::class, 'toggleActivo'])->name('destinos.toggle');

    // Paquetes (solo para admin de destinos)
    Route::prefix('mis-destinos/{idDestino}/paquetes')->name('destinos.paquetes.')->group(function () {
        Route::get('/', [PaqueteController::class, 'index'])->name('index');
        Route::get('/crear', [PaqueteController::class, 'create'])->name('create');
        Route::post('/', [PaqueteController::class, 'store'])->name('store');
        Route::get('/{idPaquete}/editar', [PaqueteController::class, 'edit'])->name('edit');
        Route::put('/{idPaquete}', [PaqueteController::class, 'update'])->name('update');
        Route::delete('/{idPaquete}', [PaqueteController::class, 'destroy'])->name('destroy');
    });

    // Comentarios
    Route::post('/centros/{id}/comentar', [ComentarioController::class, 'storeDestino'])->name('comentarios.destino.store');
    Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    // Reportes para admin de destinos (PDF)
    Route::get('/reportes-destinos', [AdminDestinoReporteController::class, 'index'])->name('reportes.destinos.index');
    Route::post('/reportes-destinos/data', [AdminDestinoReporteController::class, 'data'])->name('reportes.destinos.data');
    Route::get('/reportes-destinos/pdf', [AdminDestinoReporteController::class, 'pdf'])->name('reportes.destinos.pdf');


    // Pagos
    Route::get('/paquetes/{id}/pagar', [PagoController::class, 'show'])->name('pagos.show');
    Route::post('/paquetes/{id}/pagar', [PagoController::class, 'procesar'])->name('pagos.procesar');
    Route::get('/paquetes/{id}/confirmacion', [PagoController::class, 'confirmacion'])->name('pagos.confirmacion');


    // ventas
    Route::get('/ventas', [AdminVentasController::class, 'index'])->name('admin.ventas.index');

    // viajes turistas
    Route::get('/mis-viajes', [TuristaViajesController::class, 'index'])->name('turista.viajes');
    Route::get('/mis-viajes/{id}', [TuristaViajesController::class, 'show'])->name('turista.viajes.show');
    Route::get('/mis-viajes/{id}/imprimir', [TuristaViajesController::class, 'imprimir'])->name('turista.viajes.imprimir');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

        Route::resource('categorias', AdminCategoriaController::class);

        Route::resource('actividades', ActividadController::class)->except(['show']);
        Route::resource('recomendaciones', RecomendacionController::class)->except(['show']);



        // Reportes de usuarios (admin general)
        Route::get('/reportes-usuarios', [AdminUsuarioReporteController::class, 'index'])->name('reportes.usuarios.index');
        Route::post('/reportes-usuarios/data', [AdminUsuarioReporteController::class, 'data'])->name('reportes.usuarios.data');
        Route::get('/reportes-usuarios/pdf', [AdminUsuarioReporteController::class, 'pdf'])->name('reportes.usuarios.pdf');

        Route::get('/destino', [AdminCentroController::class, 'index'])->name('destino');
        Route::post('/destino/{id}/toggle', [AdminCentroController::class, 'toggleActivo'])->name('destino.toggle');


        Route::patch('/reportes/{id}/estado', [AdminReportesController::class, 'cambiarEstado'])->name('reportes.cambiarEstado');


        // Reportes
        Route::get('/reportes', [AdminReportesController::class, 'index'])->name('reportes');
        Route::get('/reportes/destino/{id}', [AdminReportesController::class, 'showDestino'])->name('reportes.showDestino');
        Route::post('/reportes/{id}/resolver', [AdminReportesController::class, 'resolver'])->name('reportes.resolver');
        Route::post('/reportes/{id}/rechazar', [AdminReportesController::class, 'rechazar'])->name('reportes.rechazar');
        Route::post('/reportes/comentario/{id}/eliminar', [AdminReportesController::class, 'eliminarComentario'])->name('reportes.comentario.eliminar');

        // Solicitudes (usuarios)
        Route::get('/solicitudes', [AdminSolicitudesController::class, 'index'])->name('solicitudes.index');
        Route::get('/solicitudes/crear', [AdminSolicitudesController::class, 'create'])->name('solicitudes.create');
        Route::post('/solicitudes/guardar', [AdminSolicitudesController::class, 'store'])->name('solicitudes.store');
        Route::get('/solicitudes/{id}/edit', [AdminSolicitudesController::class, 'edit'])->name('solicitudes.edit');
        Route::put('/solicitudes/{id}', [AdminSolicitudesController::class, 'update'])->name('solicitudes.update');
        Route::post('/solicitudes/{id}/toggle', [AdminSolicitudesController::class, 'toggle'])->name('solicitudes.toggle');
        Route::get('/solicitudes/{id}', [AdminSolicitudesController::class, 'show'])->name('solicitudes.show');
        Route::post('/solicitudes/{id}/aprobar', [AdminSolicitudesController::class, 'aprobar'])->name('solicitudes.aprobar');
        Route::post('/solicitudes/{id}/rechazar', [AdminSolicitudesController::class, 'rechazar'])->name('solicitudes.rechazar');

        //Respaldos
        Route::get('/respaldos', [App\Http\Controllers\Admin\AdminRespaldosController::class, 'index'])->name('respaldos');
        Route::get('/respaldos/generar', [App\Http\Controllers\Admin\AdminRespaldosController::class, 'generar'])->name('respaldos.generar');
        Route::get('/respaldos/descargar/{nombre}', [AdminRespaldosController::class, 'descargar'])->name('respaldos.descargar');
    });
});
