<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminCentroController extends Controller
{
    public function index()
    {
        $destinos = DB::table('destino as d')
            ->join('persona as p', 'p.id_persona', '=', 'd.creado_por')
            ->leftJoin('destino_categoria as dc', 'dc.id_destino', '=', 'd.id_destino')
            ->leftJoin('categoria as c', 'c.id_categoria', '=', 'dc.id_categoria')
            ->select(
                'd.id_destino',
                'd.nombre',
                'd.activo',
                'd.fecha_creacion',
                DB::raw("CONCAT(p.nombre, ' ', p.apellidos) as creador"),
                DB::raw("GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') as categorias"),
                DB::raw("(SELECT COUNT(*) FROM reporte WHERE id_destino = d.id_destino) as total_reportes"),
                DB::raw("(SELECT COUNT(*) FROM reporte WHERE id_destino = d.id_destino AND estado = 'pendiente') as reportes_pendientes")
            )
            ->groupBy('d.id_destino', 'd.nombre', 'd.activo', 'd.fecha_creacion', 'p.nombre', 'p.apellidos')
            ->orderByDesc('d.fecha_creacion')
            ->get();

        return view('admin.centro.index', compact('destinos'));
    }

    public function toggleActivo($id)
    {
        $destino = DB::table('destino')->where('id_destino', $id)->first();
        if (!$destino) abort(404);

        $nuevo = $destino->activo === 'activo' ? 'inactivo' : 'activo';
        DB::table('destino')->where('id_destino', $id)->update(['activo' => $nuevo]);

        // Actualizar rutas asociadas
        $idsRutas = DB::table('ruta_destino')->where('id_destino', $id)->pluck('id_ruta');
        if ($idsRutas->isNotEmpty()) {
            if ($nuevo === 'inactivo') {
                foreach ($idsRutas as $idRuta) {
                    DB::table('ruta')->where('id_ruta', $idRuta)->update([
                        'activo'          => 'inactivo',
                        'motivo_inactivo' => 'El destino "' . $destino->nombre . '" se encuentra suspendido.',
                    ]);
                }
            } else {
                foreach ($idsRutas as $idRuta) {
                    $inactivos = DB::table('ruta_destino')
                        ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
                        ->where('ruta_destino.id_ruta', $idRuta)
                        ->where('destino.activo', 'inactivo')
                        ->count();
                    if ($inactivos === 0) {
                        DB::table('ruta')->where('id_ruta', $idRuta)->update([
                            'activo'          => 'activo',
                            'motivo_inactivo' => null,
                        ]);
                    }
                }
            }
        }

        return back()->with('success',
            $nuevo === 'activo' ? 'Destino activado.' : 'Destino suspendido.'
        );
    }

    public function showDestino($id)
    {
        $destino = DB::table('destino as d')
            ->join('persona as p', 'p.id_persona', '=', 'd.creado_por')
            ->leftJoin('destino_categoria as dc', 'dc.id_destino', '=', 'd.id_destino')
            ->leftJoin('categoria as c', 'c.id_categoria', '=', 'dc.id_categoria')
            ->where('d.id_destino', $id)
            ->select(
                'd.*',
                DB::raw("CONCAT(p.nombre, ' ', p.apellidos) as creador"),
                DB::raw("GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') as categorias")
            )
            ->groupBy(
                'd.id_destino', 'd.nombre', 'd.descripcion', 'd.lat', 'd.lng',
                'd.telefono', 'd.activo', 'd.creado_por', 'd.fecha_creacion',
                'p.nombre', 'p.apellidos'
            )
            ->first();

        abort_if(!$destino, 404);

        $reportes = DB::table('reporte as r')
            ->join('persona as p', 'p.id_persona', '=', 'r.reportado_por')
            ->where('r.id_destino', $id)
            ->select(
                'r.id_reporte',
                'r.motivo',
                'r.descripcion',
                'r.estado',
                'r.fecha',
                DB::raw("CONCAT(p.nombre, ' ', p.apellidos) as reportado_por_nombre")
            )
            ->orderByDesc('r.fecha')
            ->get();

        $totalReportes      = $reportes->count();
        $reportesPendientes = $reportes->where('estado', 'pendiente')->count();
        $reportesResueltos  = $reportes->whereIn('estado', ['resuelto', 'rechazado'])->count();

        return view('admin.centro.show', compact(
            'destino', 'reportes', 'totalReportes', 'reportesPendientes', 'reportesResueltos'
        ));
    }

    
}