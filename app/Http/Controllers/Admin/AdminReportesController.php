<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportesController extends Controller
{
    // GET /admin/reportes
    public function index()
    {
        $total      = DB::table('reporte')->count();
        $pendientes = DB::table('reporte')->where('estado', 'pendiente')->count();
        $revisados  = DB::table('reporte')->whereIn('estado', ['resuelto', 'rechazado', 'en_revision'])->count();

        // Reportes por destino
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

        // Reportes por usuario
        $porUsuario = DB::table('reporte')
            ->join('usuario', 'reporte.reportado_por', '=', 'usuario.id_usuario')
            ->leftJoin('turista', 'usuario.id_usuario', '=', 'turista.id_usuario')
            ->leftJoin('admin_destinos', 'usuario.id_usuario', '=', 'admin_destinos.id_usuario')
            ->leftJoin('gestor_rutas', 'usuario.id_usuario', '=', 'gestor_rutas.id_usuario')
            ->select(
                'usuario.id_usuario',
                'usuario.rol',
                DB::raw('COALESCE(turista.nombre, admin_destinos.nombre, gestor_rutas.nombre) as nombre'),
                DB::raw('COALESCE(turista.apaterno, admin_destinos.apaterno, gestor_rutas.apaterno) as apaterno'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('usuario.id_usuario', 'usuario.rol', 'nombre', 'apaterno')
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

        $reportes = DB::table('reporte')
            ->join('usuario', 'reporte.reportado_por', '=', 'usuario.id_usuario')
            ->leftJoin('turista', 'usuario.id_usuario', '=', 'turista.id_usuario')
            ->leftJoin('admin_destinos', 'usuario.id_usuario', '=', 'admin_destinos.id_usuario')
            ->leftJoin('comentario', 'reporte.id_comentario', '=', 'comentario.id_comentario')
            ->where('reporte.id_destino', $id_destino)
            ->select(
                'reporte.*',
                'usuario.rol',
                DB::raw('COALESCE(turista.nombre, admin_destinos.nombre) as nombre_reporter'),
                DB::raw('COALESCE(turista.apaterno, admin_destinos.apaterno) as apaterno_reporter'),
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
}