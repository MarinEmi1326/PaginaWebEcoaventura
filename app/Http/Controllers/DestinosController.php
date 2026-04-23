<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DestinosController extends Controller
{
    public function index()
    {
        $categorias = DB::table('categoria')->orderBy('nombre')->get();
        
        $destinosRaw = DB::table('destino')
            ->join('persona', 'destino.creado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('destino.activo', 'activo')
            ->where('usuario.activo', 1)
            ->orderBy('destino.nombre')
            ->get();

        $destinos = collect();
        foreach ($destinosRaw as $d) {
            $categoriasDestino = DB::table('destino_categoria')
                ->join('categoria', 'destino_categoria.id_categoria', '=', 'categoria.id_categoria')
                ->where('destino_categoria.id_destino', $d->id_destino)
                ->pluck('categoria.nombre')
                ->toArray();

            $imagen = DB::table('imagen')
                ->where('entidad', 'destino')
                ->where('id_destino', $d->id_destino)
                ->orderBy('id_imagen')
                ->first();

            $d->categorias = collect($categoriasDestino);
            $d->imagen = $imagen;
            $destinos->push($d);
        }

        return view('centros.index', compact('destinos', 'categorias'));
    }

    public function show($id)
    {
        $destino = DB::table('destino')
            ->join('persona', 'destino.creado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('destino.id_destino', $id)
            ->where('destino.activo', 'activo')
            ->where('usuario.activo', 1)
            ->select('destino.*', 'persona.nombre as creador_nombre', 'persona.apellidos as creador_apellidos')
            ->first();

        if (!$destino) abort(404);

        $creador = (object) ['nombre' => $destino->creador_nombre, 'apellidos' => $destino->creador_apellidos];

        $categorias = DB::table('destino_categoria')
            ->join('categoria', 'destino_categoria.id_categoria', '=', 'categoria.id_categoria')
            ->where('destino_categoria.id_destino', $id)
            ->pluck('categoria.nombre')
            ->toArray();

        $actividades = DB::table('destino_actividad')
            ->join('actividad', 'destino_actividad.id_actividad', '=', 'actividad.id_actividad')
            ->where('destino_actividad.id_destino', $id)
            ->select('actividad.nombre')
            ->get();

        $recomendaciones = DB::table('destino_recomendacion')
            ->join('recomendacion', 'destino_recomendacion.id_recomendacion', '=', 'recomendacion.id_recomendacion')
            ->where('destino_recomendacion.id_destino', $id)
            ->where('recomendacion.activo', 1)
            ->pluck('recomendacion.descripcion')
            ->toArray();

        $imagenes = DB::table('imagen')
            ->where('entidad', 'destino')
            ->where('id_destino', $id)
            ->orderBy('id_imagen')
            ->get();

        $paquetes = DB::table('paquete')
            ->where('id_destino', $id)
            ->where('activo', 'activo')
            ->get();

        foreach ($paquetes as $paquete) {
            $actividadesPaquete = DB::table('paquete_actividad')
                ->join('actividad', 'paquete_actividad.id_actividad', '=', 'actividad.id_actividad')
                ->where('paquete_actividad.id_paquete', $paquete->id_paquete)
                ->orderBy('paquete_actividad.orden')
                ->select('actividad.nombre', 'paquete_actividad.minimo_personas', 'paquete_actividad.maximo_personas')
                ->get();
            $paquete->actividades = $actividadesPaquete;
        }

        $comentarios = DB::table('comentario')
            ->join('persona', 'comentario.id_persona', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('comentario.id_destino', $id)
            ->where('comentario.entidad', 'destino')
            ->where('usuario.activo', 1)
            ->select('comentario.*', 'persona.nombre', 'persona.apellidos')
            ->orderByDesc('comentario.fecha')
            ->get();

        $otrosDestinos = DB::table('destino')
            ->join('persona', 'destino.creado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('destino.activo', 'activo')
            ->where('usuario.activo', 1)
            ->where('destino.id_destino', '!=', $id)
            ->orderByRaw('RAND()')
            ->limit(3)
            ->get();

        foreach ($otrosDestinos as $od) {
            $imagenOd = DB::table('imagen')
                ->where('entidad', 'destino')
                ->where('id_destino', $od->id_destino)
                ->orderBy('id_imagen')
                ->first();
            $od->imagen = $imagenOd;
            $categoriasOd = DB::table('destino_categoria')
                ->join('categoria', 'destino_categoria.id_categoria', '=', 'categoria.id_categoria')
                ->where('destino_categoria.id_destino', $od->id_destino)
                ->pluck('categoria.nombre')
                ->toArray();
            $od->categorias = collect($categoriasOd);
        }

        // Obtener rol del usuario autenticado (si está logueado)
        $usuarioRol = null;
        if (Auth::check()) {
            $usuarioRol = Auth::user()->persona?->roles->first()?->descripcion;
        }

        return view('centros.show', compact('destino', 'categorias', 'actividades', 'recomendaciones', 'imagenes', 'paquetes', 'comentarios', 'otrosDestinos', 'creador', 'usuarioRol'));
    }
}