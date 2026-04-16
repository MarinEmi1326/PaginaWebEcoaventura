<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $persona = $user->persona;
        
        if (!$persona) {
            abort(404, 'Perfil no encontrado');
        }
        
        // Obtener roles de la persona
        $roles = $persona->roles->pluck('descripcion')->toArray();
        
        // Determinar qué vista mostrar según el rol principal o el que tenga
        if (in_array('admin_general', $roles)) {
            return view('perfil.show-admin', compact('user', 'persona'));
        }
        if (in_array('admin_destinos', $roles)) {
            return view('perfil.show-destinos', compact('user', 'persona'));
        }
        if (in_array('gestor_rutas', $roles)) {
            return view('perfil.show-rutas', compact('user', 'persona'));
        }
        if (in_array('turista', $roles)) {
            return view('perfil.show-turista', compact('user', 'persona'));
        }
        
        abort(404);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        
        // Obtener la persona asociada
        $persona = $usuario->persona;
        
        if (!$persona) {
            return back()->with('error', 'No se encontró el perfil asociado.');
        }
        
        // Obtener roles de la persona
        $roles = $persona->roles->pluck('descripcion')->toArray();
        
        // 1. Validar campos
        $rules = [
            'correo'         => 'required|email|unique:usuario,correo,' . $usuario->id_usuario . ',id_usuario',
            'password'       => 'nullable|min:8|confirmed',
            'nombre'         => 'required|string|max:60',
            'apellidos'      => 'required|string|max:120',
            'telefono'       => 'nullable|string|max:20',
            'foto_perfil'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
        
        // Redes sociales solo para admin_destinos y gestor_rutas
        if (in_array('admin_destinos', $roles) || in_array('gestor_rutas', $roles)) {
            $rules['facebook_url']  = 'nullable|url|max:255';
            $rules['instagram_url'] = 'nullable|url|max:255';
            $rules['tiktok_url']    = 'nullable|url|max:255';
        }
        
        $request->validate($rules);
        
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
        
        // 3. Actualizar Tabla PERSONA (datos unificados)
        $datosPersona = [
            'nombre'     => $request->nombre,
            'apellidos'  => $request->apellidos,
            'telefono'   => $request->telefono,
        ];
        
        // Redes sociales solo para admin_destinos y gestor_rutas
        if (in_array('admin_destinos', $roles) || in_array('gestor_rutas', $roles)) {
            $datosPersona['facebook_url']  = $request->facebook_url;
            $datosPersona['instagram_url'] = $request->instagram_url;
            $datosPersona['tiktok_url']    = $request->tiktok_url;
        }
        
        $persona->update($datosPersona);
        
        return back()->with('success', '¡Perfil y datos de acceso actualizados correctamente!');
    }
}