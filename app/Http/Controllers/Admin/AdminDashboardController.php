<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Stats generales
        $totalUsuarios  = DB::table('usuario')
            ->whereIn('rol', ['admin_destinos', 'gestor_rutas', 'turista'])
            ->where('correo_verificado', 1)
            ->count();

        $publicados     = DB::table('usuario')
            ->whereIn('rol', ['admin_destinos', 'gestor_rutas'])
            ->where('estado', 'aprobado')
            ->count();

        $pendientes     = DB::table('usuario')
            ->whereIn('rol', ['admin_destinos', 'gestor_rutas'])
            ->where('estado', 'pendiente')
            ->where('correo_verificado', 1)
            ->count();

        $rechazados     = DB::table('usuario')
            ->whereIn('rol', ['admin_destinos', 'gestor_rutas'])
            ->where('estado', 'rechazado')
            ->count();

        // Cola de aprobación — solicitudes pendientes
        $colaAprobacion = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->whereIn('u.rol', ['admin_destinos', 'gestor_rutas'])
            ->where('u.estado', 'pendiente')
            ->where('u.correo_verificado', 1)
            ->select(
                'u.id_usuario',
                'u.rol',
                'u.fecha_solicitud',
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre"),
                DB::raw("COALESCE(ad.apaterno, gr.apaterno) as apaterno")
            )
            ->orderBy('u.fecha_solicitud', 'asc')
            ->limit(5)
            ->get();

        // Actividad reciente — destinos creados más recientes
        $actividadReciente = DB::table('destino as d')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'd.creado_por')
            ->select(
                'd.id_destino',
                'd.nombre',
                'd.fecha_creacion',
                'd.activo',
                DB::raw("COALESCE(ad.nombre, 'Sin autor') as nombre_admin"),
                DB::raw("COALESCE(ad.apaterno, '') as apaterno_admin")
            )
            ->orderByDesc('d.fecha_creacion')
            ->limit(5)
            ->get();

        return view('admin.index', compact(
            'totalUsuarios',
            'publicados',
            'pendientes',
            'rechazados',
            'colaAprobacion',
            'actividadReciente'
        ));
    }
}