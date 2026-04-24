<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Rol;
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
            // ============================================
            // CREAR NUEVO USUARIO (NUEVA ESTRUCTURA)
            // ============================================
            $usuario = Usuario::create([
                'correo'      => $googleUser->getEmail(),
                'google_id'   => $googleUser->getId(),
                'foto_perfil' => $googleUser->getAvatar(),
                'password'    => bcrypt(Str::random(24)),
                'activo'      => true,
                'estado'      => 'aprobado',
                'correo_verificado' => 1,
                'fecha_solicitud' => now(),
                'fecha_respuesta' => now(),
            ]);

            // Crear persona
            $nombre = $googleUser->user['given_name'] ?? 'Sin nombre';
            $apellidos = $googleUser->user['family_name'] ?? 'Sin apellido';
            
            $persona = Persona::create([
                'id_usuario' => $usuario->id_usuario,
                'nombre'     => $nombre,
                'apellidos'  => $apellidos,
                'telefono'   => null,
            ]);

            // Asignar rol TURISTA
            $rolTurista = Rol::where('descripcion', 'turista')->first();
            if ($rolTurista) {
                $persona->roles()->attach($rolTurista->id_rol);
            }

        } else {
            // ============================================
            // USUARIO YA EXISTE (NUEVA ESTRUCTURA)
            // ============================================
            if ($usuario->estado === 'pendiente') {
                return redirect('/login')->with('error', 'Tu cuenta aún no ha sido aprobada.');
            }
            if ($usuario->estado === 'rechazado') {
                return redirect('/login')->with('error', 'Tu cuenta fue rechazada.');
            }
            if (!$usuario->activo) {
                return redirect('/login')->with('error', 'Tu cuenta está desactivada.');
            }

            // Actualizar google_id y foto
            $usuario->update([
                'google_id'   => $googleUser->getId(),
                'foto_perfil' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($usuario);

        // ============================================
        // REDIRIGIR SEGÚN ROL (desde persona_rol)
        // ============================================
        $persona = $usuario->persona;
        $roles = $persona?->roles->pluck('descripcion')->toArray() ?? [];

        if (in_array('admin_general', $roles)) {
            return redirect()->route('admin.index');
        }
        if (in_array('admin_destinos', $roles)) {
            return redirect()->route('misdestinos.index');
        }
        if (in_array('gestor_rutas', $roles)) {
            return redirect()->route('rutas.index');
        }
        if (in_array('turista', $roles)) {
            return redirect('/');
        }

        return redirect('/login')->with('error', 'Rol no reconocido');
    }
}