<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Destino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    // Helper para obtener el id_persona del usuario autenticado
    private function getPersonaId()
    {
        $persona = DB::table('persona')->where('id_usuario', Auth::id())->first();
        return $persona ? $persona->id_persona : null;
    }

    public function index()
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $rutas = Ruta::where('creado_por', $personaId)
            ->orderByDesc('fecha_creacion')
            ->get();
        $total = $rutas->count();
        return view('admin.gestor_rutas.index', compact('rutas', 'total'));
    }

    // ── Vista pública de rutas para turistas ──
    public function publicIndex()
    {
        // Obtener rutas con el nombre del creador en una sola consulta
        $rutas = DB::table('ruta')
            ->leftJoin('persona', 'ruta.creado_por', '=', 'persona.id_persona')
            ->where('ruta.activo', 'activo')
            ->select(
                'ruta.*',
                'persona.nombre as creador_nombre',
                'persona.apellidos as creador_apellidos'
            )
            ->orderByDesc('ruta.fecha_creacion')
            ->get();

        $idsRutas = $rutas->pluck('id_ruta')->toArray();

        // Contar paradas
        $paradas = DB::table('ruta_destino')
            ->whereIn('id_ruta', $idsRutas)
            ->select('id_ruta', DB::raw('COUNT(*) as total'))
            ->groupBy('id_ruta')
            ->get()
            ->keyBy('id_ruta');

        // Primeras imágenes
        $imagenes = DB::table('imagen')
            ->where('entidad', 'ruta')
            ->whereIn('id_ruta', $idsRutas)
            ->orderBy('id_imagen')
            ->get()
            ->groupBy('id_ruta')
            ->map(fn($g) => $g->first());

        foreach ($rutas as $ruta) {
            $ruta->total_paradas = $paradas[$ruta->id_ruta]->total ?? 0;
            $ruta->imagen = $imagenes[$ruta->id_ruta]->ruta_archivo ?? null;
        }

        return view('rutas.ruta', compact('rutas'));
    }

    public function create()
    {
        $destinos = Destino::where('activo', 'activo')
            ->orderBy('nombre')
            ->get();

        return view('admin.gestor_rutas.create', compact('destinos'));
    }

    // ── INFO DESTINO: endpoint que consume el JavaScript del formulario ──
    public function infoDestino($id)
    {
        $destino = DB::table('destino')->where('id_destino', $id)->first();

        if (!$destino) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        $actividades = DB::table('actividad')
            ->join('destino_actividad', 'actividad.id_actividad', '=', 'destino_actividad.id_actividad')
            ->where('destino_actividad.id_destino', $id)
            ->select('actividad.id_actividad', 'actividad.nombre', 'actividad.dificultad')
            ->get();

        return response()->json([
            'descripcion' => $destino->descripcion,
            'lat'         => $destino->lat,
            'lng'         => $destino->lng,
            'actividades' => $actividades,
        ]);
    }

    // ── STORE: guardar la ruta ──
    public function store(Request $request)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $request->validate([
            'nombre'                 => 'required|string|max:120',
            'descripcion'            => 'required|string',
            'dificultad'             => 'required|in:baja,media,alta',
            'distancia_km'           => 'nullable|numeric',
            'duracion_estimada'      => 'nullable|string|max:50',
            'recomendaciones'        => 'nullable|string',
            'fecha_inicio_operacion' => 'nullable|date',
            'fecha_fin_operacion'    => 'nullable|date|after_or_equal:fecha_inicio_operacion',
            'destinos'               => 'required|array|min:1',
            'destinos.*'             => 'required|exists:destino,id_destino',
            'fotos'                  => 'nullable|array',
            'fotos.*'                => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request, $personaId) {

            $ruta = Ruta::create([
                'nombre'                 => $request->nombre,
                'descripcion'            => $request->descripcion,
                'dificultad'             => $request->dificultad,
                'distancia_km'           => $request->distancia_km,
                'duracion_estimada'      => $request->duracion_estimada,
                'recomendaciones'        => $request->recomendaciones,
                'fecha_inicio_operacion' => $request->fecha_inicio_operacion,
                'fecha_fin_operacion'    => $request->fecha_fin_operacion,
                'activo'                 => 'activo',
                'creado_por'             => $personaId,
                'fecha_creacion'         => now(),
            ]);

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $rutaArchivo = $foto->store('rutas', 'public');
                    DB::table('imagen')->insert([
                        'entidad'      => 'ruta',
                        'id_ruta'      => $ruta->id_ruta,
                        'id_destino'   => null,
                        'ruta_archivo' => $rutaArchivo,
                        'subida_por'   => $personaId,
                        'fecha'        => now(),
                    ]);
                }
            }

            foreach ($request->destinos as $index => $idDestino) {
                DB::table('ruta_destino')->insert([
                    'id_ruta'    => $ruta->id_ruta,
                    'id_destino' => $idDestino,
                    'orden'      => $index + 1,
                ]);

                $actividades = $request->input('actividades_' . $idDestino, []);
                foreach ($actividades as $idActividad) {
                    DB::table('ruta_destino_actividad')->insert([
                        'id_ruta'      => $ruta->id_ruta,
                        'id_destino'   => $idDestino,
                        'id_actividad' => $idActividad,
                    ]);
                }
            }
        });

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta guardada correctamente.');
    }

    public function show($id)
    {
        // 1. Traer la ruta
        $ruta = DB::table('ruta')->where('id_ruta', $id)->where('activo', 'activo')->first();
        abort_if(!$ruta, 404);

        // 2. Traer los destinos de la ruta en orden
        $destinos = DB::table('ruta_destino')
            ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
            ->where('ruta_destino.id_ruta', $id)
            ->orderBy('ruta_destino.orden')
            ->select(
                'destino.id_destino',
                'destino.nombre',
                'destino.descripcion',
                'destino.lat',
                'destino.lng',
                'ruta_destino.orden'
            )
            ->get();

        // 3. Para cada destino traer sus actividades en esta ruta
        foreach ($destinos as $destino) {
            $destino->actividades = DB::table('actividad')
                ->join('ruta_destino_actividad', 'actividad.id_actividad', '=', 'ruta_destino_actividad.id_actividad')
                ->where('ruta_destino_actividad.id_ruta', $id)
                ->where('ruta_destino_actividad.id_destino', $destino->id_destino)
                ->select('actividad.nombre')
                ->get();
        }

        // 4. Comentarios
        $comentarios = DB::table('comentario')
            ->join('persona', 'comentario.id_persona', '=', 'persona.id_persona')
            ->where('comentario.entidad', 'ruta')
            ->where('comentario.id_ruta', $id)
            ->orderByDesc('comentario.fecha')
            ->select('comentario.id_comentario', 'comentario.comentario', 'comentario.fecha', 'persona.nombre', 'persona.apellidos')
            ->get();

        // 5. Recomendaciones
        $recomendaciones = DB::table('ruta_recomendacion')
            ->join('recomendacion', 'ruta_recomendacion.id_recomendacion', '=', 'recomendacion.id_recomendacion')
            ->where('ruta_recomendacion.id_ruta', $id)
            ->where('recomendacion.activo', 1)
            ->pluck('recomendacion.descripcion')
            ->toArray();

        // 6. JSON para el mapa (solo destinos con coordenadas válidas)
        $destinosJson = $destinos->filter(function ($d) {
            return !is_null($d->lat) && !is_null($d->lng) && is_numeric($d->lat) && is_numeric($d->lng);
        })->map(function ($d) {
            return [
                'orden'  => $d->orden,
                'nombre' => $d->nombre,
                'lat'    => (float) $d->lat,
                'lng'    => (float) $d->lng,
            ];
        })->values()->toJson(); // values() reindexa el array

        return view('rutas.show', compact('ruta', 'destinos', 'comentarios', 'recomendaciones', 'destinosJson'));
    }

    // ── EDIT: mostrar formulario de edición ──
    public function edit($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $ruta = Ruta::where('id_ruta', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$ruta, 404);

        $imagenes = DB::table('imagen')
            ->where('entidad', 'ruta')
            ->where('id_ruta', $id)
            ->get();

        $destinos = DB::table('ruta_destino')
            ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
            ->where('ruta_destino.id_ruta', $id)
            ->orderBy('ruta_destino.orden')
            ->select('destino.nombre', 'ruta_destino.orden')
            ->get();

        return view('admin.gestor_rutas.edit', compact('ruta', 'imagenes', 'destinos'));
    }

    // ── UPDATE: guardar cambios ──
    public function update(Request $request, $id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $ruta = Ruta::where('id_ruta', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$ruta, 404);

        $request->validate([
            'nombre'          => 'required|string|max:120',
            'descripcion'     => 'required|string',
            'recomendaciones' => 'nullable|string',
            'fotos'           => 'nullable|array',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::table('ruta')
            ->where('id_ruta', $id)
            ->update([
                'nombre'          => $request->nombre,
                'descripcion'     => $request->descripcion,
                'recomendaciones' => $request->recomendaciones,
            ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $rutaArchivo = $foto->store('rutas', 'public');
                DB::table('imagen')->insert([
                    'entidad'      => 'ruta',
                    'id_ruta'      => $id,
                    'id_destino'   => null,
                    'ruta_archivo' => $rutaArchivo,
                    'subida_por'   => $personaId,
                    'fecha'        => now(),
                ]);
            }
        }

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroyImagen($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $imagen = DB::table('imagen')->where('id_imagen', $id)->first();
        abort_if(!$imagen, 404);

        $ruta = Ruta::where('id_ruta', $imagen->id_ruta)
            ->where('creado_por', $personaId)
            ->first();
        abort_if(!$ruta, 403);

        Storage::disk('public')->delete($imagen->ruta_archivo);
        DB::table('imagen')->where('id_imagen', $id)->delete();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $ruta = Ruta::where('id_ruta', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$ruta, 404);

        $imagenes = DB::table('imagen')
            ->where('entidad', 'ruta')
            ->where('id_ruta', $id)
            ->get();

        foreach ($imagenes as $img) {
            Storage::disk('public')->delete($img->ruta_archivo);
        }
        DB::table('imagen')->where('id_ruta', $id)->delete();

        DB::table('ruta_destino_actividad')->where('id_ruta', $id)->delete();
        DB::table('ruta_destino')->where('id_ruta', $id)->delete();

        $ruta->delete();

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }
}
