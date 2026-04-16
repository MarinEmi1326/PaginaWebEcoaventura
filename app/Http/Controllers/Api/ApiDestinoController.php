<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApiDestinoController extends Controller
{
    // Helper para obtener la persona del usuario autenticado
    private function getPersona($userId)
    {
        return DB::table('persona')->where('id_usuario', $userId)->first();
    }

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
        
        // CAMBIADO: usar persona en lugar de turista
        $destino->comentarios = DB::table('comentario')
            ->join('persona', 'comentario.id_persona', '=', 'persona.id_persona')
            ->where('comentario.id_destino', $id)
            ->where('comentario.entidad', 'destino')
            ->select('comentario.*', 'persona.nombre', 'persona.apellidos')
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

        // CAMBIADO: usar persona en lugar de turista
        $comentarios = DB::table('comentario')
            ->join('persona', 'comentario.id_persona', '=', 'persona.id_persona')
            ->where('comentario.id_destino', $id)
            ->where('comentario.entidad', 'destino')
            ->select('comentario.*', 'persona.nombre', 'persona.apellidos')
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
        
        // CAMBIADO: verificar rol desde persona
        $persona = $this->getPersona($user->id_usuario);
        $roles = DB::table('persona_rol')
            ->join('rol', 'persona_rol.id_rol', '=', 'rol.id_rol')
            ->where('persona_rol.id_persona', $persona->id_persona)
            ->pluck('rol.descripcion')
            ->toArray();

        if (!in_array('admin_destinos', $roles)) {
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
            'categorias'  => 'nullable|array',
            'categorias.*' => 'exists:categoria,id_categoria',
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

        // CAMBIADO: Insertar destino con id_persona
        $id_destino = DB::table('destino')->insertGetId([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'telefono'        => $request->telefono,
            'recomendaciones' => $request->recomendaciones,
            'lat'             => $request->lat,
            'lng'             => $request->lng,
            'activo'          => 'activo',
            'creado_por'      => $persona->id_persona,  // CAMBIADO: id_persona
            'fecha_creacion'  => now(),
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
                    'entidad'      => 'destino',
                    'id_destino'   => $id_destino,
                    'ruta_archivo' => $ruta,
                    'subida_por'   => $persona->id_persona,
                    'fecha'        => now(),
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
        
        // CAMBIADO: verificar rol turista desde persona
        $persona = $this->getPersona($user->id_usuario);
        $roles = DB::table('persona_rol')
            ->join('rol', 'persona_rol.id_rol', '=', 'rol.id_rol')
            ->where('persona_rol.id_persona', $persona->id_persona)
            ->pluck('rol.descripcion')
            ->toArray();

        if (!in_array('turista', $roles)) {
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

        // CAMBIADO: usar id_persona en lugar de id_turista
        DB::table('comentario')->insert([
            'id_persona' => $persona->id_persona,
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

    // POST /api/destinos/{id}/reportar
    public function reportar(Request $request, $id)
    {
        $request->validate([
            'motivo'      => 'required|in:contenido_inapropiado,informacion_falsa,spam,lenguaje_ofensivo,derechos_autor,otro',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $persona = $this->getPersona($user->id_usuario);

        $destino = DB::table('destino')->where('id_destino', $id)->where('activo', 'activo')->first();

        if (!$destino) {
            return response()->json(['success' => false, 'message' => 'Destino no encontrado.'], 404);
        }

        // Verificar que no haya reportado ya este destino
        $yaReporto = DB::table('reporte')
            ->where('reportado_por', $persona->id_persona)
            ->where('tipo_objeto', 'destino')
            ->where('id_destino', $id)
            ->exists();

        if ($yaReporto) {
            return response()->json(['success' => false, 'message' => 'Ya reportaste este destino.'], 409);
        }

        DB::table('reporte')->insert([
            'reportado_por' => $persona->id_persona,
            'tipo_objeto'   => 'destino',
            'id_destino'    => $id,
            'motivo'        => $request->motivo,
            'descripcion'   => $request->descripcion,
            'estado'        => 'pendiente',
            'fecha'         => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Reporte enviado correctamente.'], 201);
    }

    // GET /api/favoritos
    public function favoritos(Request $request)
    {
        $user = $request->user();
        $persona = $this->getPersona($user->id_usuario);
        
        $roles = DB::table('persona_rol')
            ->join('rol', 'persona_rol.id_rol', '=', 'rol.id_rol')
            ->where('persona_rol.id_persona', $persona->id_persona)
            ->pluck('rol.descripcion')
            ->toArray();

        if (!in_array('turista', $roles)) {
            return response()->json(['success' => false, 'message' => 'No eres turista'], 403);
        }

        // CAMBIADO: usar id_persona en lugar de id_turista
        $favoritos = DB::table('favorito')
            ->join('destino', 'favorito.id_destino', '=', 'destino.id_destino')
            ->where('favorito.id_persona', $persona->id_persona)
            ->select('destino.*')
            ->get();

        foreach ($favoritos as $d) {
            $d->imagenes = DB::table('imagen')
                ->where('id_destino', $d->id_destino)->get();
            $d->categorias = DB::table('categoria')
                ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
                ->where('destino_categoria.id_destino', $d->id_destino)
                ->select('categoria.*')->get();
        }

        return response()->json(['success' => true, 'data' => $favoritos]);
    }

    // POST /api/favoritos/{id}/toggle
    public function toggleFavorito(Request $request, $id)
    {
        $user = $request->user();
        $persona = $this->getPersona($user->id_usuario);
        
        $roles = DB::table('persona_rol')
            ->join('rol', 'persona_rol.id_rol', '=', 'rol.id_rol')
            ->where('persona_rol.id_persona', $persona->id_persona)
            ->pluck('rol.descripcion')
            ->toArray();

        if (!in_array('turista', $roles)) {
            return response()->json(['success' => false, 'message' => 'No eres turista'], 403);
        }

        // CAMBIADO: usar id_persona en lugar de id_turista
        $existe = DB::table('favorito')
            ->where('id_persona', $persona->id_persona)
            ->where('id_destino', $id)
            ->exists();

        if ($existe) {
            DB::table('favorito')
                ->where('id_persona', $persona->id_persona)
                ->where('id_destino', $id)
                ->delete();
            return response()->json(['success' => true, 'favorito' => false, 'message' => 'Eliminado de favoritos']);
        } else {
            DB::table('favorito')->insert([
                'id_persona' => $persona->id_persona,
                'id_destino' => $id,
                'fecha' => now(),
            ]);
            return response()->json(['success' => true, 'favorito' => true, 'message' => 'Agregado a favoritos']);
        }
    }
}