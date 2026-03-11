<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinosController extends Controller
{
    public function index()
    {
        $destinos = DB::table('destino')
            ->where('activo', 'activo')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        foreach ($destinos as $d) {
            $d->imagen = DB::table('imagen')
                ->where('id_destino', $d->id_destino)
                ->where('entidad', 'destino')
                ->first();

            $d->categorias = DB::table('categoria')
                ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
                ->where('destino_categoria.id_destino', $d->id_destino)
                ->pluck('categoria.nombre');
        }

        $categorias = DB::table('categoria')->orderBy('nombre')->get();

        return view('centros.index', compact('destinos', 'categorias'));
    }

    public function show($id)
    {
        $destino = DB::table('destino')
            ->where('id_destino', $id)
            ->where('activo', 'activo')
            ->first();

        if (!$destino) abort(404);

        $imagenes = DB::table('imagen')
            ->where('id_destino', $id)
            ->where('entidad', 'destino')
            ->get();

        $categorias = DB::table('categoria')
            ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
            ->where('destino_categoria.id_destino', $id)
            ->pluck('categoria.nombre');

        $actividades = DB::table('actividad')
            ->join('destino_actividad', 'actividad.id_actividad', '=', 'destino_actividad.id_actividad')
            ->where('destino_actividad.id_destino', $id)
            ->get();

        $paquetes = DB::table('paquete')
            ->where('id_destino', $id)
            ->where('activo', 'activo')
            ->get();

        // Comentarios con nombre del turista
        $comentarios = DB::table('comentario')
            ->join('turista', 'comentario.id_turista', '=', 'turista.id_turista')
            ->where('comentario.id_destino', $id)
            ->where('comentario.entidad', 'destino')
            ->orderBy('comentario.fecha', 'desc')
            ->select('comentario.*', 'turista.nombre', 'turista.apaterno')
            ->get();

        // Otros destinos relacionados
        $otrosDestinos = DB::table('destino')
            ->join('destino_categoria', 'destino.id_destino', '=', 'destino_categoria.id_destino')
            ->whereIn('destino_categoria.id_categoria', function($q) use ($id) {
                $q->select('id_categoria')
                  ->from('destino_categoria')
                  ->where('id_destino', $id);
            })
            ->where('destino.id_destino', '!=', $id)
            ->where('destino.activo', 'activo')
            ->select('destino.*')
            ->distinct()
            ->limit(3)
            ->get();

        foreach ($otrosDestinos as $od) {
            $od->imagen = DB::table('imagen')
                ->where('id_destino', $od->id_destino)
                ->where('entidad', 'destino')
                ->first();
            $od->categorias = DB::table('categoria')
                ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
                ->where('destino_categoria.id_destino', $od->id_destino)
                ->pluck('categoria.nombre');
        }

        return view('centros.show', compact(
            'destino', 'imagenes', 'categorias',
            'actividades', 'paquetes', 'comentarios', 'otrosDestinos'
        ));
    }
}