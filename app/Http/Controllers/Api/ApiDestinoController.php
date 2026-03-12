<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApiDestinoController extends Controller
{
    // GET /api/destinos
    public function index()
    {
        $destinos = DB::table('destino')
            ->where('activo', 'activo')
            ->get();

        foreach ($destinos as $d) {
            $d->imagenes    = DB::table('imagen')->where('id_destino', $d->id_destino)->get();
            $d->categorias  = DB::table('categoria')
                ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
                ->where('destino_categoria.id_destino', $d->id_destino)
                ->select('categoria.*')
                ->get();
            $d->actividades = DB::table('actividad')
                ->join('destino_actividad', 'actividad.id_actividad', '=', 'destino_actividad.id_actividad')
                ->where('destino_actividad.id_destino', $d->id_destino)
                ->select('actividad.*')
                ->get();
        }

        return response()->json([
            'success' => true,
            'total'   => $destinos->count(),
            'data'    => $destinos,
        ]);
    }

    // GET /api/destinos/{id}
    public function show($id)
    {
        $destino = DB::table('destino')->where('id_destino', $id)->where('activo', 'activo')->first();

        if (!$destino) {
            return response()->json(['success' => false, 'message' => 'Destino no encontrado.'], 404);
        }

        $destino->imagenes    = DB::table('imagen')->where('id_destino', $id)->get();
        $destino->categorias  = DB::table('categoria')
            ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
            ->where('destino_categoria.id_destino', $id)
            ->select('categoria.*')->get();
        $destino->actividades = DB::table('actividad')
            ->join('destino_actividad', 'actividad.id_actividad', '=', 'destino_actividad.id_actividad')
            ->where('destino_actividad.id_destino', $id)
            ->select('actividad.*')->get();
        $destino->paquetes    = DB::table('paquete')->where('id_destino', $id)->where('activo', 'activo')->get();
        $destino->comentarios = DB::table('comentario')
            ->join('turista', 'comentario.id_turista', '=', 'turista.id_turista')
            ->where('comentario.id_destino', $id)
            ->where('comentario.entidad', 'destino')
            ->select('comentario.*', 'turista.nombre', 'turista.apaterno')
            ->orderByDesc('comentario.fecha')
            ->get();

        return response()->json(['success' => true, 'data' => $destino]);
    }

    // GET /api/destinos/categoria/{id_categoria}
    public function porCategoria($id_categoria)
    {
        $destinos = DB::table('destino')
            ->join('destino_categoria', 'destino.id_destino', '=', 'destino_categoria.id_destino')
            ->where('destino_categoria.id_categoria', $id_categoria)
            ->where('destino.activo', 'activo')
            ->select('destino.*')
            ->get();

        foreach ($destinos as $d) {
            $d->imagenes = DB::table('imagen')->where('id_destino', $d->id_destino)->get();
        }

        return response()->json([
            'success' => true,
            'total'   => $destinos->count(),
            'data'    => $destinos,
        ]);
    }

    // GET /api/destinos/{id}/comentarios
    public function comentarios($id)
    {
        $destino = DB::table('destino')->where('id_destino', $id)->first();
        if (!$destino) {
            return response()->json(['success' => false, 'message' => 'Destino no encontrado.'], 404);
        }

        $comentarios = DB::table('comentario')
            ->join('turista', 'comentario.id_turista', '=', 'turista.id_turista')
            ->where('comentario.id_destino', $id)
            ->where('comentario.entidad', 'destino')
            ->select('comentario.*', 'turista.nombre', 'turista.apaterno')
            ->orderByDesc('comentario.fecha')
            ->get();

        return response()->json([
            'success' => true,
            'total'   => $comentarios->count(),
            'data'    => $comentarios,
        ]);
    }

    // POST /api/destinos
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->rol !== 'admin_destinos') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los administradores de destinos pueden crear destinos.',
            ], 403);
        }

        $request->validate([
            'nombre'      => 'required|string|max:120',
            'descripcion' => 'required|string',
            'telefono'    => 'nullable|string|max:20',
            'recomendaciones' => 'nullable|string',
            'lat'         => 'nullable|numeric',
            'lng'         => 'nullable|numeric',
            'google_place_id' => 'nullable|string|max:120',
            'categorias'  => 'nullable|array',
            'categorias.*'=> 'exists:categoria,id_categoria',
            'actividades_existentes'   => 'nullable|array',
            'actividades_existentes.*' => 'exists:actividad,id_actividad',
            'nuevas_actividades'       => 'nullable|array',
            'nuevas_actividades.*.nombre' => 'required_with:nuevas_actividades|string|max:80',
            'nuevas_actividades.*.dificultad' => 'required_with:nuevas_actividades|in:baja,media,alta',
            'paquetes'    => 'nullable|array',
            'paquetes.*.nombre' => 'required_with:paquetes|string|max:120',
            'fotos'       => 'nullable|array',
            'fotos.*'     => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $adminDestinos = DB::table('admin_destinos')->where('id_usuario', $user->id_usuario)->first();

        // Insertar destino
        $id_destino = DB::table('destino')->insertGetId([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'telefono'        => $request->telefono,
            'recomendaciones' => $request->recomendaciones,
            'lat'             => $request->lat,
            'lng'             => $request->lng,
            'google_place_id' => $request->google_place_id,
            'activo'          => 'activo',
            'creado_por'      => $user->id_usuario,
            'id_admin_destinos' => $adminDestinos->id_admin_destinos,
        ]);

        // Categorías
        if ($request->categorias) {
            foreach ($request->categorias as $id_cat) {
                DB::table('destino_categoria')->insert([
                    'id_destino'   => $id_destino,
                    'id_categoria' => $id_cat,
                ]);
            }
        }

        // Actividades existentes
        if ($request->actividades_existentes) {
            foreach ($request->actividades_existentes as $id_act) {
                DB::table('destino_actividad')->insert([
                    'id_destino'   => $id_destino,
                    'id_actividad' => $id_act,
                ]);
            }
        }

        // Nuevas actividades
        if ($request->nuevas_actividades) {
            foreach ($request->nuevas_actividades as $act) {
                if (empty($act['nombre'])) continue;
                $id_act = DB::table('actividad')->insertGetId([
                    'nombre'            => $act['nombre'],
                    'dificultad'        => $act['dificultad'] ?? 'baja',
                    'duracion_estimada' => $act['duracion'] ?? null,
                    'minimo_personas'   => $act['min_personas'] ?? null,
                    'recomendacion'     => $act['recomendacion'] ?? null,
                ]);
                DB::table('destino_actividad')->insert([
                    'id_destino'   => $id_destino,
                    'id_actividad' => $id_act,
                ]);
            }
        }

        // Paquetes
        if ($request->paquetes) {
            foreach ($request->paquetes as $paq) {
                if (empty($paq['nombre'])) continue;
                DB::table('paquete')->insert([
                    'id_destino'      => $id_destino,
                    'nombre'          => $paq['nombre'],
                    'descripcion'     => $paq['descripcion'] ?? null,
                    'precio'          => $paq['precio'] ?? null,
                    'minimo_personas' => $paq['minimo_personas'] ?? null,
                    'activo'          => 'activo',
                ]);
            }
        }

        // Imágenes
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $ruta = $foto->store('destinos', 'public');
                DB::table('imagen')->insert([
                    'id_destino' => $id_destino,
                    'ruta'       => $ruta,
                ]);
            }
        }

        $destino = DB::table('destino')->where('id_destino', $id_destino)->first();

        return response()->json([
            'success' => true,
            'message' => 'Destino creado correctamente.',
            'data'    => $destino,
        ], 201);
    }

    // POST /api/destinos/{id}/comentar
    public function comentar(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        if ($user->rol !== 'turista') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los turistas pueden comentar.',
            ], 403);
        }

        $destino = DB::table('destino')->where('id_destino', $id)->where('activo', 'activo')->first();

        if (!$destino) {
            return response()->json([
                'success' => false,
                'message' => 'Destino no encontrado.',
            ], 404);
        }

        $turista = DB::table('turista')->where('id_usuario', $user->id_usuario)->first();

        DB::table('comentario')->insert([
            'id_turista' => $turista->id_turista,
            'entidad'    => 'destino',
            'id_destino' => $id,
            'comentario' => $request->comentario,
            'fecha'      => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comentario publicado correctamente.',
        ], 201);
    }
}