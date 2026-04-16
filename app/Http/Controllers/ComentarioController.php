<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComentarioController extends Controller
{
    public function storeDestino(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $persona = $user->persona;

        if (!$persona) {
            return back()->with('error', 'No se encontró tu perfil de persona.');
        }

        if (!$persona->tieneRol('turista')) {
            return back()->with('error', 'Solo los turistas pueden comentar en destinos.');
        }

        DB::table('comentario')->insert([
            'id_persona' => $persona->id_persona,
            'entidad'    => 'destino',
            'id_destino' => $id,
            'comentario' => $request->comentario,
            'fecha'      => now(),
        ]);

        return back()->with('success', 'Comentario publicado correctamente.');
    }

    public function storeRuta(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $persona = $user->persona;

        if (!$persona) {
            return back()->with('error', 'No se encontró tu perfil de persona.');
        }

        if (!$persona->tieneRol('turista')) {
            return back()->with('error', 'Solo los turistas pueden comentar en rutas.');
        }

        DB::table('comentario')->insert([
            'id_persona' => $persona->id_persona,
            'entidad'    => 'ruta',
            'id_ruta'    => $id,
            'comentario' => $request->comentario,
            'fecha'      => now(),
        ]);

        return back()->with('success', 'Comentario publicado correctamente.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $persona = $user->persona;

        if (!$persona || !$persona->tieneRol('admin_general')) {
            abort(403, 'No tienes permiso para eliminar comentarios.');
        }

        DB::table('comentario')
            ->where('id_comentario', $id)
            ->delete();

        return back()->with('success', 'Comentario eliminado correctamente.');
    }
}