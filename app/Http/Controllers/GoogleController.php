<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use App\Models\Turista;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
        $usuario = Usuario::where('correo', $googleUser->getEmail())->first();

        if (!$usuario) {
            // No existe → crear como turista
            $usuario = Usuario::create([
                'correo'      => $googleUser->getEmail(),
                'google_id'   => $googleUser->getId(),
                'foto_perfil' => $googleUser->getAvatar(),
                'password'    => bcrypt(Str::random(24)),
                'rol'         => 'turista',
                'activo'      => true,
                'estado'      => 'aprobado',
            ]);

            Turista::create([
                'nombre'     => $googleUser->user['given_name'] ?? 'Sin nombre',
                'apaterno'   => $googleUser->user['family_name'] ?? 'Sin apellido',
                'id_usuario' => $usuario->id_usuario,
            ]);

        } else {
            // Ya existe → verificar estado
            if ($usuario->estado === 'pendiente') {
                return redirect('/login')->with('error', 'Tu cuenta aún no ha sido aprobada por el administrador.');
            }
            if ($usuario->estado === 'rechazado') {
                return redirect('/login')->with('error', 'Tu cuenta fue rechazada. Contacta al administrador.');
            }
            if (!$usuario->activo) {
                return redirect('/login')->with('error', 'Tu cuenta está desactivada.');
            }

            // Actualizar google_id si aún no lo tenía
            $usuario->update([
                'google_id'   => $googleUser->getId(),
                'foto_perfil' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($usuario);

        // Redirigir según rol
        return match($usuario->rol) {
            'admin_general'  => redirect('/admin/dashboard'),
            'admin_destinos' => redirect('/destinos/dashboard'),
            'gestor_rutas'   => redirect('/rutas/dashboard'),
            'turista'        => redirect('/turista/dashboard'),
        };
    }
}