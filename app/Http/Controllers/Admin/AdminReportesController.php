<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportesController extends Controller
{
    // Helper para obtener la persona del usuario
    private function getPersona($userId)
    {
        return DB::table('persona')->where('id_usuario', $userId)->first();
    }

    // GET /admin/reportes
    public function index()
    {
        $total      = DB::table('reporte')->count();
        $pendientes = DB::table('reporte')->where('estado', 'pendiente')->count();
        $revisados  = DB::table('reporte')->whereIn('estado', ['resuelto', 'rechazado', 'en_revision'])->count();

        // ============================================
        // REPORTES POR DESTINO (CORREGIDO)
        // ============================================
        $porDestino = DB::table('reporte')
            ->join('destino', 'reporte.id_destino', '=', 'destino.id_destino')
            ->select(
                'destino.id_destino',
                'destino.nombre',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN reporte.estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes")
            )
            ->whereNotNull('reporte.id_destino')
            ->groupBy('destino.id_destino', 'destino.nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ============================================
        // REPORTES POR USUARIO (CORREGIDO - usando persona)
        // ============================================
        $porUsuario = DB::table('reporte')
            ->join('persona', 'reporte.reportado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->select(
                'usuario.id_usuario',
                'persona.nombre',
                'persona.apellidos',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('usuario.id_usuario', 'persona.nombre', 'persona.apellidos')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.reportes.index', compact('total', 'pendientes', 'revisados', 'porDestino', 'porUsuario'));
    }

    // GET /admin/reportes/destino/{id}
    public function showDestino($id_destino)
    {
        $destino = DB::table('destino')->where('id_destino', $id_destino)->first();
        if (!$destino) abort(404);

        $total      = DB::table('reporte')->where('id_destino', $id_destino)->count();
        $pendientes = DB::table('reporte')->where('id_destino', $id_destino)->where('estado', 'pendiente')->count();
        $revisados  = DB::table('reporte')->where('id_destino', $id_destino)->whereIn('estado', ['resuelto', 'rechazado'])->count();

        // ============================================
        // REPORTES DEL DESTINO (CORREGIDO - usando persona)
        // ============================================
        $reportes = DB::table('reporte')
            ->join('persona', 'reporte.reportado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->leftJoin('comentario', 'reporte.id_comentario', '=', 'comentario.id_comentario')
            ->where('reporte.id_destino', $id_destino)
            ->select(
                'reporte.*',
                'persona.nombre as nombre_reporter',
                'persona.apellidos as apellidos_reporter',
                'comentario.comentario as texto_comentario'
            )
            ->orderByDesc('reporte.fecha')
            ->get();

        return view('admin.reportes.show', compact('destino', 'total', 'pendientes', 'revisados', 'reportes'));
    }

    // POST /admin/reportes/{id}/resolver
    public function resolver($id)
    {
        DB::table('reporte')->where('id_reporte', $id)->update(['estado' => 'resuelto']);
        return back()->with('success', 'Reporte marcado como resuelto.');
    }

    // POST /admin/reportes/{id}/rechazar
    public function rechazar($id)
    {
        DB::table('reporte')->where('id_reporte', $id)->update(['estado' => 'rechazado']);
        return back()->with('success', 'Reporte rechazado.');
    }

    // POST /admin/reportes/comentario/{id}/eliminar
    public function eliminarComentario($id_comentario)
    {
        // Marcar todos los reportes del comentario como resueltos
        DB::table('reporte')
            ->where('id_comentario', $id_comentario)
            ->update(['estado' => 'resuelto']);

        // Eliminar el comentario
        DB::table('comentario')->where('id_comentario', $id_comentario)->delete();

        return back()->with('success', 'Comentario eliminado y reportes resueltos.');
    }
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,resuelto,rechazado'
        ]);

        DB::table('reporte')
            ->where('id_reporte', $id)
            ->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado del reporte actualizado correctamente.');
    }
}
