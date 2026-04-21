<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminRutaReporteController extends Controller
{
    // Verificar que el usuario sea admin_general (igual que en destinos, pero con rol diferente)
    private function checkAdminGeneral()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'No autenticado.');
        }

        $rol = DB::table('persona_rol as pr')
            ->join('persona as p', 'p.id_persona', '=', 'pr.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('p.id_usuario', $user->id_usuario)
            ->value('r.descripcion');

        if ($rol !== 'admin_general') {
            abort(403, 'No autorizado. Solo administradores generales pueden acceder.');
        }
        return true;
    }

    public function index()
    {
        $this->checkAdminGeneral();
        // Obtener lista de gestores (personas que han creado al menos una ruta)
        $gestores = DB::table('persona')
            ->join('ruta', 'persona.id_persona', '=', 'ruta.creado_por')
            ->select('persona.id_persona', 'persona.nombre', 'persona.apellidos')
            ->distinct()
            ->orderBy('persona.nombre')
            ->get();
        return view('admin.reportes-rutas.index', compact('gestores'));
    }

    public function data(Request $request)
    {
        $this->checkAdminGeneral();
        $tipo = $request->input('tipo'); // 'por_gestor' o 'por_dificultad'
        $filtros = $request->only(['gestor_id', 'dificultad', 'estado', 'fecha_desde', 'fecha_hasta']);

        if ($tipo === 'por_gestor') {
            $data = $this->getRutasPorGestor($filtros);
        } else {
            $data = $this->getRutasPorDificultad($filtros);
        }

        return response()->json(['data' => $data]);
    }

    public function pdf(Request $request)
    {
        $this->checkAdminGeneral();
        $tipo = $request->input('tipo');
        $filtros = $request->only(['gestor_id', 'dificultad', 'estado', 'fecha_desde', 'fecha_hasta']);

        if ($tipo === 'por_gestor') {
            $data = $this->getRutasPorGestor($filtros);
            $titulo = 'Reporte: Rutas por gestor';
        } else {
            $data = $this->getRutasPorDificultad($filtros);
            $titulo = 'Reporte: Rutas por dificultad';
        }

        $fechaGeneracion = Carbon::now()->format('d/m/Y H:i:s');
        $pdf = Pdf::loadView('admin.reportes-rutas.pdf', compact('data', 'titulo', 'filtros', 'fechaGeneracion'));
        return $pdf->download('reporte_rutas_' . now()->timestamp . '.pdf');
    }

    // Consulta: Rutas por gestor (con filtros opcionales)
    private function getRutasPorGestor($filtros)
    {
        $query = DB::table('ruta as r')
            ->join('persona as p', 'r.creado_por', '=', 'p.id_persona')
            ->select(
                'r.id_ruta',
                'r.nombre as ruta_nombre',
                'p.nombre as gestor_nombre',
                'p.apellidos as gestor_apellidos',
                'r.dificultad',
                'r.activo',
                'r.fecha_creacion',
                'r.duracion_estimada',
                'r.distancia_km'
            );

        if (!empty($filtros['gestor_id'])) {
            $query->where('r.creado_por', $filtros['gestor_id']);
        }
        if (!empty($filtros['dificultad'])) {
            $query->where('r.dificultad', $filtros['dificultad']);
        }
        if (!empty($filtros['estado'])) {
            $query->where('r.activo', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('r.fecha_creacion', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('r.fecha_creacion', '<=', $filtros['fecha_hasta']);
        }

        $query->orderBy('p.nombre')->orderBy('r.nombre');
        return $query->get();
    }

    // Consulta: Rutas por dificultad (con filtros opcionales)
    private function getRutasPorDificultad($filtros)
    {
        $query = DB::table('ruta as r')
            ->join('persona as p', 'r.creado_por', '=', 'p.id_persona')
            ->select(
                'r.id_ruta',
                'r.nombre as ruta_nombre',
                'p.nombre as gestor_nombre',
                'p.apellidos as gestor_apellidos',
                'r.dificultad',
                'r.activo',
                'r.fecha_creacion',
                'r.duracion_estimada',
                'r.distancia_km'
            );

        if (!empty($filtros['gestor_id'])) {
            $query->where('r.creado_por', $filtros['gestor_id']);
        }
        if (!empty($filtros['dificultad'])) {
            $query->where('r.dificultad', $filtros['dificultad']);
        }
        if (!empty($filtros['estado'])) {
            $query->where('r.activo', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('r.fecha_creacion', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('r.fecha_creacion', '<=', $filtros['fecha_hasta']);
        }

        $query->orderBy('r.dificultad')->orderBy('r.nombre');
        return $query->get();
    }
}