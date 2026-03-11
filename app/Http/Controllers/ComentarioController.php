<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class ComentarioController extends Controller
{
    public function storeDestino(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        // Verificar que sea turista
        if ($user->rol !== 'turista') {
            return back()->with('error', 'Solo los turistas pueden comentar.');
        }

        $turista = DB::table('turista')->where('id_usuario', $user->id_usuario)->first();

        if (!$turista) {
            return back()->with('error', 'No se encontró tu perfil de turista.');
        }

        DB::table('comentario')->insert([
            'id_turista'  => $turista->id_turista,
            'entidad'     => 'destino',
            'id_destino'  => $id,
            'comentario'  => $request->comentario,
            'fecha'       => now(),
        ]);

        return back()->with('success', 'Comentario publicado.');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->rol !== 'admin_general') {
            abort(403);
        }

        DB::table('comentario')->where('id_comentario', $id)->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}