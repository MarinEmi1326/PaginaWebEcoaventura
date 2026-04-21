<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    // Helper para obtener la persona del usuario autenticado
    private function getPersona($userId)
    {
        return DB::table('persona')->where('id_usuario', $userId)->first();
    }

    // Helper para verificar si el usuario tiene un rol específico
    private function tieneRol($userId, $rolNombre)
    {
        $persona = $this->getPersona($userId);
        if (!$persona) return false;

        $rol = DB::table('persona_rol')
            ->join('rol', 'persona_rol.id_rol', '=', 'rol.id_rol')
            ->where('persona_rol.id_persona', $persona->id_persona)
            ->where('rol.descripcion', $rolNombre)
            ->first();

        return $rol !== null;
    }

    // POST /destinos/{id}/reportar
    public function reportarDestino(Request $request, $id)
    {
        $request->validate([
            'motivo'      => 'required|in:contenido_inapropiado,informacion_falsa,spam,lenguaje_ofensivo,derechos_autor,otro',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        // CAMBIADO: verificar rol turista desde persona_rol
        if (!$this->tieneRol($user->id_usuario, 'turista')) {
            return back()->with('error', 'Solo los turistas pueden reportar destinos.');
        }

        $destino = DB::table('destino')->where('id_destino', $id)->first();
        if (!$destino) abort(404);

        // Obtener la persona (reportado_por ahora es id_persona)
        $persona = $this->getPersona($user->id_usuario);

        // Verificar que no haya reportado ya este destino
        $yaReporto = DB::table('reporte')
            ->where('reportado_por', $persona->id_persona)  // CAMBIADO: usar id_persona
            ->where('tipo_objeto', 'destino')
            ->where('id_destino', $id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($yaReporto) {
            return back()->with('error', 'Ya enviaste un reporte pendiente para este destino.');
        }

        DB::table('reporte')->insert([
            'reportado_por' => $persona->id_persona,  // CAMBIADO: usar id_persona
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

        // CAMBIADO: verificar roles desde persona_rol
        $esTurista = $this->tieneRol($user->id_usuario, 'turista');
        $esAdminDestinos = $this->tieneRol($user->id_usuario, 'admin_destinos');

        if (!$esTurista && !$esAdminDestinos) {
            return back()->with('error', 'No tienes permiso para reportar comentarios.');
        }

        $comentario = DB::table('comentario')->where('id_comentario', $id)->first();
        if (!$comentario) abort(404);

        // Obtener la persona
        $persona = $this->getPersona($user->id_usuario);

        // Verificar que no haya reportado ya este comentario
        $yaReporto = DB::table('reporte')
            ->where('reportado_por', $persona->id_persona)  // CAMBIADO: usar id_persona
            ->where('tipo_objeto', 'comentario')
            ->where('id_comentario', $id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($yaReporto) {
            return back()->with('error', 'Ya enviaste un reporte pendiente para este comentario.');
        }

        DB::table('reporte')->insert([
            'reportado_por' => $persona->id_persona,  // CAMBIADO: usar id_persona
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