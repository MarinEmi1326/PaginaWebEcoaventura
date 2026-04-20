<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComentarioController extends Controller
{
    /**
     * Guardar comentario en un destino (solo para turistas)
     */
    public function storeDestino(Request $request, $idDestino)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para comentar.');
        }

        $user = Auth::user();
        $persona = $user->persona;

        // Verificar que la persona existe y tiene rol 'turista'
        if (!$persona) {
            return back()->with('error', 'No se encontró tu perfil de persona.');
        }

        // Obtener el rol del usuario a través de la relación persona->roles
        $rol = $persona->roles->first()?->descripcion;
        if ($rol !== 'turista') {
            return back()->with('error', 'Solo los turistas pueden comentar destinos.');
        }

        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        // Insertar comentario
        DB::table('comentario')->insert([
            'id_persona'   => $persona->id_persona,
            'entidad'      => 'destino',
            'id_destino'   => $idDestino,
            'comentario'   => $request->comentario,
            'fecha'        => now(),
        ]);

        return back()->with('success', 'Comentario publicado correctamente.');
    }

    /**
     * Guardar comentario en una ruta (si aplica)
     */
    public function storeRuta(Request $request, $idRuta)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para comentar.');
        }

        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona) {
            return back()->with('error', 'No se encontró tu perfil de persona.');
        }

        $rol = $persona->roles->first()?->descripcion;
        if ($rol !== 'turista') {
            return back()->with('error', 'Solo los turistas pueden comentar rutas.');
        }

        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        DB::table('comentario')->insert([
            'id_persona' => $persona->id_persona,
            'entidad'    => 'ruta',
            'id_ruta'    => $idRuta,
            'comentario' => $request->comentario,
            'fecha'      => now(),
        ]);

        return back()->with('success', 'Comentario publicado correctamente.');
    }

    /**
     * Eliminar comentario (solo para admin_general)
     */
    public function destroy($idComentario)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $persona = $user->persona;
        $rol = $persona->roles->first()?->descripcion;

        if ($rol !== 'admin_general') {
            abort(403, 'No autorizado.');
        }

        DB::table('comentario')->where('id_comentario', $idComentario)->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}