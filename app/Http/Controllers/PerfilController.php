<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Importante para la contraseña
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return match ($user->rol) {
            'admin_general'  => view('perfil.show-admin', compact('user')),
            'admin_destinos' => view('perfil.show-destinos', compact('user')),
            'gestor_rutas'   => view('perfil.show-rutas', compact('user')),
            'turista'        => view('perfil.show-turista', compact('user')),
            default          => abort(404),
        };
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        
        // Obtenemos la relación dinámica según el rol
        $perfil = match ($usuario->rol) {
            'admin_general'  => $usuario->adminGeneral,
            'admin_destinos' => $usuario->adminDestinos,
            'gestor_rutas'   => $usuario->gestorRutas,
            'turista'        => $usuario->turista,
            default          => null,
        };

        if (!$perfil) {
            return back()->with('error', 'No se encontró el perfil asociado.');
        }

        // 1. Validar campos (Usuario + Perfil)
        $request->validate([
            // Validamos correo único ignorando el ID actual
            'correo'         => 'required|email|unique:usuario,correo,' . $usuario->id_usuario . ',id_usuario',
            'password'       => 'nullable|min:8|confirmed', // 'confirmed' busca un campo password_confirmation
            'nombre'         => 'required|string|max:60',
            'apaterno'       => 'required|string|max:60',
            'amaterno'       => 'nullable|string|max:60',
            'telefono'       => 'nullable|string|max:20',
            'facebook_url'   => 'nullable|url|max:255',
            'instagram_url'  => 'nullable|url|max:255',
            'tiktok_url'     => 'nullable|url|max:255',
            'foto_perfil'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Actualizar Tabla USUARIO (Correo, Password y Foto)
        $datosUsuario = [
            'correo' => $request->correo,
        ];

        // Solo si escribió una nueva contraseña
        if ($request->filled('password')) {
            $datosUsuario['password'] = Hash::make($request->password);
        }

        // Lógica de Foto de Perfil
        if ($request->hasFile('foto_perfil')) {
            if ($usuario->foto_perfil) {
                Storage::delete('public/' . $usuario->foto_perfil);
            }
            $datosUsuario['foto_perfil'] = $request->file('foto_perfil')->store('perfiles', 'public');
        }

        $usuario->update($datosUsuario);

        // 3. Preparar y actualizar Tabla de Perfil Específica
        $datosPerfil = [
            'nombre'   => $request->nombre,
            'apaterno' => $request->apaterno,
            'amaterno' => $request->amaterno,
            'telefono' => $request->telefono,
        ];

        // Redes sociales solo para roles permitidos
        if (in_array($usuario->rol, ['admin_destinos', 'gestor_rutas'])) {
            $datosPerfil['facebook_url']  = $request->facebook_url;
            $datosPerfil['instagram_url'] = $request->instagram_url;
            $datosPerfil['tiktok_url']    = $request->tiktok_url;
        }

        $perfil->update($datosPerfil);

        return back()->with('success', '¡Perfil y datos de acceso actualizados correctamente!');
    }
}