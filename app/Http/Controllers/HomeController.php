<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $destacados = DB::table('destino as d')
            ->leftJoin('imagen as i', function ($join) {
                $join->on('i.id_destino', '=', 'd.id_destino')
                    ->where('i.entidad', '=', 'destino');
            })
            ->where('d.activo', 'activo')
            ->select(
                'd.id_destino',
                'd.nombre',
                'd.descripcion',
                'd.telefono',
                DB::raw('MIN(i.ruta_archivo) as ruta_archivo')
            )
            ->groupBy('d.id_destino', 'd.nombre', 'd.descripcion', 'd.telefono')
            ->orderBy('d.fecha_creacion', 'desc')
            ->limit(3)
            ->get();

        foreach ($destacados as $d) {
            $d->imagen = (object)['ruta_archivo' => $d->ruta_archivo];
            $d->categorias = DB::table('categoria')
                ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
                ->where('destino_categoria.id_destino', $d->id_destino)
                ->pluck('categoria.nombre');
        }

        $rutasDestacadas = DB::table('ruta as r')
            ->leftJoin('imagen as i', function ($join) {
                $join->on('i.id_ruta', '=', 'r.id_ruta')
                    ->where('i.entidad', '=', 'ruta');
            })
            ->where('r.activo', 'activo')
            ->select(
                'r.id_ruta',
                'r.nombre',
                'r.descripcion',
                'r.dificultad',
                'r.duracion_estimada',
                'r.distancia_km',
                DB::raw('MIN(i.ruta_archivo) as imagen')
            )
            ->groupBy('r.id_ruta', 'r.nombre', 'r.descripcion', 'r.dificultad', 'r.duracion_estimada', 'r.distancia_km')
            ->orderBy('r.fecha_creacion', 'desc')
            ->limit(3)
            ->get();

        foreach ($rutasDestacadas as $r) {
            $r->total_paradas = DB::table('ruta_destino')
                ->where('id_ruta', $r->id_ruta)
                ->count();
        }

        return view('home', compact('destacados', 'rutasDestacadas'));
    }
}
