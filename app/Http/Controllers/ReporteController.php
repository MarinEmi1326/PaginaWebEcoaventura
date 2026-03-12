<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    // POST /destinos/{id}/reportar
    public function reportarDestino(Request $request, $id)
    {
        $request->validate([
            'motivo'      => 'required|in:contenido_inapropiado,informacion_falsa,spam,lenguaje_ofensivo,derechos_autor,otro',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        if ($user->rol !== 'turista') {
            return back()->with('error', 'Solo los turistas pueden reportar destinos.');
        }

        $destino = DB::table('destino')->where('id_destino', $id)->first();
        if (!$destino) abort(404);

        // Verificar que no haya reportado ya este destino
        $yaReporto = DB::table('reporte')
            ->where('reportado_por', $user->id_usuario)
            ->where('tipo_objeto', 'destino')
            ->where('id_destino', $id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($yaReporto) {
            return back()->with('error', 'Ya enviaste un reporte pendiente para este destino.');
        }

        DB::table('reporte')->insert([
            'reportado_por' => $user->id_usuario,
            'tipo_objeto'   => 'destino',
            'id_destino'    => $id,
            'motivo'        => $request->motivo,
            'descripcion'   => $request->descripcion,
            'estado'        => 'pendiente',
            'fecha'         => now(),
        ]);

        return back()->with('success', 'Reporte enviado. El administrador lo revisará pronto.');
    }

    // POST /comentarios/{id}/reportar
    public function reportarComentario(Request $request, $id)
    {
        $request->validate([
            'motivo'      => 'required|in:contenido_inapropiado,informacion_falsa,spam,lenguaje_ofensivo,derechos_autor,otro',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        if (!in_array($user->rol, ['turista', 'admin_destinos'])) {
            return back()->with('error', 'No tienes permiso para reportar comentarios.');
        }

        $comentario = DB::table('comentario')->where('id_comentario', $id)->first();
        if (!$comentario) abort(404);

        // Verificar que no haya reportado ya este comentario
        $yaReporto = DB::table('reporte')
            ->where('reportado_por', $user->id_usuario)
            ->where('tipo_objeto', 'comentario')
            ->where('id_comentario', $id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($yaReporto) {
            return back()->with('error', 'Ya enviaste un reporte pendiente para este comentario.');
        }

        DB::table('reporte')->insert([
            'reportado_por' => $user->id_usuario,
            'tipo_objeto'   => 'comentario',
            'id_comentario' => $id,
            'id_destino'    => $comentario->id_destino,
            'motivo'        => $request->motivo,
            'descripcion'   => $request->descripcion,
            'estado'        => 'pendiente',
            'fecha'         => now(),
        ]);

        return back()->with('success', 'Reporte enviado. El administrador lo revisará pronto.');
    }
}