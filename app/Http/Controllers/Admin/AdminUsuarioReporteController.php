<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminUsuarioReporteController extends Controller
{
    private function checkAdminGeneral()
    {
        $user = auth()->user();
        if (!$user) abort(403, 'No autenticado.');

        $rol = DB::table('persona_rol as pr')
            ->join('persona as p', 'p.id_persona', '=', 'pr.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('p.id_usuario', $user->id_usuario)
            ->value('r.descripcion');

        if ($rol !== 'admin_general') {
            abort(403, 'Solo el administrador general puede acceder.');
        }
        return true;
    }

    public function index()
    {
        $this->checkAdminGeneral();
        return view('admin.reportes-usuarios.index');
    }

    public function data(Request $request)
    {
        $this->checkAdminGeneral();
        $tipo    = $request->input('tipo');
        $filtros = $request->only(['rol', 'estado', 'activo']);

        $data = $tipo === 'por_rol'
            ? $this->getUsuariosPorRol($filtros)
            : $this->getUsuariosPorEstado($filtros);

        return response()->json(['data' => $data]);
    }

    public function pdf(Request $request)
    {
        $this->checkAdminGeneral();
        $tipo    = $request->input('tipo');
        $filtros = $request->only(['rol', 'estado', 'activo']);

        if ($tipo === 'por_rol') {
            $data   = $this->getUsuariosPorRol($filtros);
            $titulo = 'Reporte: Usuarios por Rol';
        } else {
            $data   = $this->getUsuariosPorEstado($filtros);
            $titulo = 'Reporte: Usuarios por Estado';
        }

        $fechaGeneracion = Carbon::now()->format('d/m/Y H:i:s');
        $pdf = Pdf::loadView('admin.reportes-usuarios.pdf', compact('data', 'titulo', 'filtros', 'fechaGeneracion', 'tipo'));
        return $pdf->download('reporte_usuarios_' . now()->timestamp . '.pdf');
    }

    private function getUsuariosPorRol($filtros)
    {
        $query = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->select(
                'u.id_usuario',
                DB::raw("CONCAT(p.nombre, ' ', p.apellidos) as nombre_completo"),
                'u.correo',
                'r.descripcion as rol',
                'u.estado',
                'u.activo',
                'u.fecha_solicitud'
            )
            ->where('r.descripcion', '!=', 'admin_general')
            ->orderBy('r.descripcion')
            ->orderBy('p.nombre');

        if (!empty($filtros['rol'])) {
            $query->where('r.descripcion', $filtros['rol']);
        }
        if (!empty($filtros['estado'])) {
            $query->where('u.estado', $filtros['estado']);
        }
        if ($filtros['activo'] !== null && $filtros['activo'] !== '') {
            $query->where('u.activo', $filtros['activo']);
        }

        return $query->get();
    }

    private function getUsuariosPorEstado($filtros)
    {
        $query = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->select(
                'u.id_usuario',
                DB::raw("CONCAT(p.nombre, ' ', p.apellidos) as nombre_completo"),
                'u.correo',
                'u.estado',
                'r.descripcion as rol',
                'u.activo',
                'u.fecha_solicitud'
            )
            ->where('r.descripcion', '!=', 'admin_general')
            ->orderBy('u.estado')
            ->orderBy('p.nombre');

        if (!empty($filtros['estado'])) {
            $query->where('u.estado', $filtros['estado']);
        }
        if (!empty($filtros['rol'])) {
            $query->where('r.descripcion', $filtros['rol']);
        }
        if ($filtros['activo'] !== null && $filtros['activo'] !== '') {
            $query->where('u.activo', $filtros['activo']);
        }

        return $query->get();
    }
}