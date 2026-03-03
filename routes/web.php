<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSitioController;

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\Admin\AdminSolicitudesController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FacebookController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('home'))->name('home');

Route::view('/cultura', 'cultura')->name('cultura'); 
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



Route::get('/mapa', function () {
    return view('mapa');
})->name('mapa');

// Route::get('/publicar-facebook', [FacebookController::class, 'publicar']);
// Route::get('/ver-facebook', [FacebookController::class, 'verPosts']);
Route::get('/turismo-responsable', function () {
    return view('turismo-responsable');
})->name('turismo-responsable');


Route::get('/cultura', function () {
    return view('cultura');
})->name('cultura');


Route::get('/ruta', function () {
    return view('ruta');
})->name('ruta');



// apenas se agrego las vistas 
// Route::get('/destinos', [DestinoController::class, 'index'])->name('destinos.index');

// Route::get('/destinos/{tipo}', [DestinoController::class, 'index'])
//     ->whereIn('tipo', ['turisticos','ecoturisticos','balnearios'])
//     ->name('destinos.tipo');

// Route::get('/destino/{slug}', [DestinoController::class, 'show'])
//     ->name('destinos.show');





// LOGIN CON GOOGLE
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt('12345678'),
        ]
    );

    Auth::login($user);

    return redirect()->route('home');
});



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

});
