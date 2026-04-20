<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminDestinoReporteController extends Controller
{
    // Verificar que el usuario sea admin_destinos (consulta directa a BD)
    private function checkAdminDestinos()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'No autenticado.');
        }

        // Consulta directa para evitar problemas con Eloquent
        $rol = DB::table('persona_rol as pr')
            ->join('persona as p', 'p.id_persona', '=', 'pr.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('p.id_usuario', $user->id_usuario)
            ->value('r.descripcion');

        if ($rol !== 'admin_destinos') {
            abort(403, 'No autorizado. Solo administradores de destinos pueden acceder.');
        }
        return true;
    }

    public function index()
    {
        $this->checkAdminDestinos();
        $categorias = DB::table('categoria')->orderBy('nombre')->get();
        return view('admin.reportes-destinos.index', compact('categorias'));
    }

    public function data(Request $request)
    {
        $this->checkAdminDestinos();
        $categoria = $request->input('categoria');
        $tipo = $request->input('tipo');
        $filtros = $request->only(['categoria_id', 'estado', 'fecha_desde', 'fecha_hasta']);

        if ($categoria === 'destinos') {
            $data = $this->getDestinosPorCategoria($filtros);
        } else {
            if ($tipo === 'con_paquetes') {
                $data = $this->getDestinosConPaquetes($filtros);
            } else {
                $data = $this->getDestinosSinPaquetes($filtros);
            }
        }

        return response()->json(['data' => $data]);
    }

    public function pdf(Request $request)
    {
        $this->checkAdminDestinos();
        $categoria = $request->input('categoria');
        $tipo = $request->input('tipo');
        $filtros = $request->only(['categoria_id', 'estado', 'fecha_desde', 'fecha_hasta']);

        if ($categoria === 'destinos') {
            $data = $this->getDestinosPorCategoria($filtros);
            $titulo = 'Reporte: Destinos por categoría';
        } else {
            if ($tipo === 'con_paquetes') {
                $data = $this->getDestinosConPaquetes($filtros);
                $titulo = 'Reporte: Destinos con paquetes';
            } else {
                $data = $this->getDestinosSinPaquetes($filtros);
                $titulo = 'Reporte: Destinos sin paquetes';
            }
        }

        $fechaGeneracion = Carbon::now()->format('d/m/Y H:i:s');
        $pdf = Pdf::loadView('admin.reportes-destinos.pdf', compact('data', 'titulo', 'filtros', 'fechaGeneracion'));
        return $pdf->download('reporte_' . now()->timestamp . '.pdf');
    }

    // Consulta: Destinos por categoría
    private function getDestinosPorCategoria($filtros)
    {
        $query = DB::table('destino as d')
            ->leftJoin('destino_categoria as dc', 'd.id_destino', '=', 'dc.id_destino')
            ->leftJoin('categoria as c', 'dc.id_categoria', '=', 'c.id_categoria')
            ->select(
                'd.id_destino',
                'd.nombre as destino_nombre',
                'd.activo',
                'd.fecha_creacion',
                DB::raw("GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') as categorias")
            )
            ->groupBy('d.id_destino', 'd.nombre', 'd.activo', 'd.fecha_creacion');

        if (!empty($filtros['categoria_id'])) {
            $query->havingRaw("FIND_IN_SET(?, categorias)", [$filtros['categoria_id']]);
        }
        if (!empty($filtros['estado'])) {
            $query->where('d.activo', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('d.fecha_creacion', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('d.fecha_creacion', '<=', $filtros['fecha_hasta']);
        }

        return $query->get();
    }

    // Consulta: Destinos con paquetes
    private function getDestinosConPaquetes($filtros)
    {
        $query = DB::table('destino as d')
            ->join('paquete as p', 'd.id_destino', '=', 'p.id_destino')
            ->select(
                'd.id_destino',
                'd.nombre as destino_nombre',
                'd.activo',
                'd.fecha_creacion',
                DB::raw('COUNT(p.id_paquete) as total_paquetes'),
                DB::raw("GROUP_CONCAT(p.nombre SEPARATOR ', ') as paquetes_nombres")
            )
            ->groupBy('d.id_destino', 'd.nombre', 'd.activo', 'd.fecha_creacion');

        if (!empty($filtros['estado'])) {
            $query->where('d.activo', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('d.fecha_creacion', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('d.fecha_creacion', '<=', $filtros['fecha_hasta']);
        }

        return $query->get();
    }

    // Consulta: Destinos sin paquetes
    private function getDestinosSinPaquetes($filtros)
    {
        $query = DB::table('destino as d')
            ->leftJoin('paquete as p', 'd.id_destino', '=', 'p.id_destino')
            ->select(
                'd.id_destino',
                'd.nombre as destino_nombre',
                'd.activo',
                'd.fecha_creacion'
            )
            ->whereNull('p.id_paquete')
            ->groupBy('d.id_destino', 'd.nombre', 'd.activo', 'd.fecha_creacion');

        if (!empty($filtros['estado'])) {
            $query->where('d.activo', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('d.fecha_creacion', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('d.fecha_creacion', '<=', $filtros['fecha_hasta']);
        }

        return $query->get();
    }
}