<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCentroController extends Controller
{
    public function index()
    {
        $destinos = DB::table('destino as d')
            ->leftJoin('destino_categoria as dc', 'dc.id_destino', '=', 'd.id_destino')
            ->leftJoin('categoria as c', 'c.id_categoria', '=', 'dc.id_categoria')
            ->leftJoin('reporte as r', function($join) {
                $join->on('r.id_destino', '=', 'd.id_destino')
                     ->whereNull('r.id_comentario');
            })
            ->select(
                'd.id_destino',
                'd.nombre',
                'd.activo',
                DB::raw("GROUP_CONCAT(DISTINCT c.nombre SEPARATOR ', ') as categorias"),
                DB::raw("COUNT(DISTINCT r.id_reporte) as total_reportes"),
                DB::raw("SUM(CASE WHEN r.estado = 'pendiente' THEN 1 ELSE 0 END) as reportes_pendientes")
            )
            ->groupBy('d.id_destino', 'd.nombre', 'd.activo')
            ->orderByDesc('total_reportes')
            ->get();

        return view('admin.centro.index', compact('destinos'));
    }

    public function toggleActivo($id)
    {
        $destino = DB::table('destino')->where('id_destino', $id)->first();
        if (!$destino) abort(404);

        $nuevo = $destino->activo === 'activo' ? 'inactivo' : 'activo';
        DB::table('destino')->where('id_destino', $id)->update(['activo' => $nuevo]);

        return back()->with('success',
            $nuevo === 'activo' ? 'Destino activado.' : 'Destino suspendido.'
        );
    }
}