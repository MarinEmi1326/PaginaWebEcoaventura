<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ================================
        // Stats generales
        // ================================

        // Total de usuarios (admin_destinos, gestor_rutas, turista) verificados
        $totalUsuarios = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas', 'turista'])
            ->where('u.correo_verificado', 1)
            ->distinct('u.id_usuario')
            ->count('u.id_usuario');

        // Usuarios aprobados (admin_destinos y gestor_rutas)
        $publicados = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
            ->where('u.estado', 'aprobado')
            ->distinct('u.id_usuario')
            ->count('u.id_usuario');

        // Solicitudes pendientes (admin_destinos y gestor_rutas)
        $pendientes = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
            ->where('u.estado', 'pendiente')
            ->where('u.correo_verificado', 1)
            ->distinct('u.id_usuario')
            ->count('u.id_usuario');

        // Usuarios rechazados (admin_destinos y gestor_rutas)
        $rechazados = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
            ->where('u.estado', 'rechazado')
            ->distinct('u.id_usuario')
            ->count('u.id_usuario');

        // ================================
        // Cola de aprobación (últimas 5 solicitudes pendientes)
        // ================================

        $colaAprobacion = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
            ->where('u.estado', 'pendiente')
            ->where('u.correo_verificado', 1)
            ->select(
                'u.id_usuario',
                'u.fecha_solicitud',
                'r.descripcion as rol',
                'p.nombre',
                'p.apellidos'
            )
            ->orderBy('u.fecha_solicitud', 'asc')
            ->limit(5)
            ->get();

        // ================================
        // Actividad reciente (últimos 5 destinos creados)
        // ================================

        $actividadReciente = DB::table('destino as d')
            ->leftJoin('persona as p', 'p.id_persona', '=', 'd.creado_por')
            ->select(
                'd.id_destino',
                'd.nombre',
                'd.fecha_creacion',
                'd.activo',
                'p.nombre as nombre_admin',
                'p.apellidos as apellidos_admin'
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