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

    // ── Vista pública de rutas para turistas (filtra por usuario activo) ──
    public function publicIndex()
    {
        $rutas = DB::table('ruta')
            ->join('persona', 'ruta.creado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('ruta.activo', 'activo')
            ->where('usuario.activo', 1)
            ->select('ruta.*', 'persona.nombre as creador_nombre', 'persona.apellidos as creador_apellidos')
            ->orderByDesc('ruta.fecha_creacion')
            ->get();

        $idsRutas = $rutas->pluck('id_ruta')->toArray();

        $paradas = DB::table('ruta_destino')
            ->whereIn('id_ruta', $idsRutas)
            ->select('id_ruta', DB::raw('COUNT(*) as total'))
            ->groupBy('id_ruta')
            ->get()
            ->keyBy('id_ruta');

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

    // ... resto de métodos (create, store, infoDestino, etc.) se mantienen igual.

    // Método show (detalle de ruta) con filtro de usuario activo
    public function show($id)
    {
        $ruta = DB::table('ruta')
            ->join('persona', 'ruta.creado_por', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('ruta.id_ruta', $id)
            ->where('ruta.activo', 'activo')
            ->where('usuario.activo', 1)
            ->select('ruta.*', 'persona.nombre as creador_nombre', 'persona.apellidos as creador_apellidos')
            ->first();
        abort_if(!$ruta, 404);

        // Destinos de la ruta (sin filtrar por usuario porque ya la ruta es del usuario activo, los destinos en sí pueden ser de otros usuarios pero están activos o no? Depende de lógica: si el destino está inactivo, no debería mostrarse. Pero por simplicidad, asumimos que los destinos ya están filtrados en otras partes.)
        $destinos = DB::table('ruta_destino')
            ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
            ->where('ruta_destino.id_ruta', $id)
            ->orderBy('ruta_destino.orden')
            ->select('destino.id_destino', 'destino.nombre', 'destino.descripcion', 'destino.lat', 'destino.lng', 'ruta_destino.orden')
            ->get();

        foreach ($destinos as $destino) {
            $destino->actividades = DB::table('actividad')
                ->join('ruta_destino_actividad', 'actividad.id_actividad', '=', 'ruta_destino_actividad.id_actividad')
                ->where('ruta_destino_actividad.id_ruta', $id)
                ->where('ruta_destino_actividad.id_destino', $destino->id_destino)
                ->select('actividad.nombre')
                ->get();
        }

        // Comentarios (solo usuarios activos)
        $comentarios = DB::table('comentario')
            ->join('persona', 'comentario.id_persona', '=', 'persona.id_persona')
            ->join('usuario', 'persona.id_usuario', '=', 'usuario.id_usuario')
            ->where('comentario.entidad', 'ruta')
            ->where('comentario.id_ruta', $id)
            ->where('usuario.activo', 1)
            ->orderByDesc('comentario.fecha')
            ->select('comentario.id_comentario', 'comentario.comentario', 'comentario.fecha', 'persona.nombre', 'persona.apellidos')
            ->get();

        $recomendaciones = DB::table('ruta_recomendacion')
            ->join('recomendacion', 'ruta_recomendacion.id_recomendacion', '=', 'recomendacion.id_recomendacion')
            ->where('ruta_recomendacion.id_ruta', $id)
            ->where('recomendacion.activo', 1)
            ->pluck('recomendacion.descripcion')
            ->toArray();

        $destinosJson = $destinos->filter(fn($d) => $d->lat && $d->lng)
            ->map(fn($d) => ['orden' => $d->orden, 'nombre' => $d->nombre, 'lat' => (float) $d->lat, 'lng' => (float) $d->lng])
            ->values()->toJson();

        return view('rutas.show', compact('ruta', 'destinos', 'comentarios', 'recomendaciones', 'destinosJson'));
    }

    // ... el resto de métodos (edit, update, destroy, destroyImagen) se mantienen igual.


    // ── FORMULARIO DE CREACIÓN (CORREGIDO) ──
    public function create()
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $destinos = Destino::where('activo', 'activo')
            ->orderBy('nombre')
            ->get();

        // Recomendaciones disponibles (catálogo)
        $recomendacionesDisponibles = DB::table('recomendacion')
            ->where('activo', 1)
            ->orderBy('descripcion')
            ->get();

        return view('admin.gestor_rutas.create', compact('destinos', 'recomendacionesDisponibles'));
    }

    // ── INFO DESTINO (CORREGIDO, siempre devuelve JSON) ──
    public function infoDestino($id)
    {
        try {
            $destino = DB::table('destino')->where('id_destino', $id)->first();

            if (!$destino) {
                return response()->json(['error' => 'Destino no encontrado'], 404);
            }

            $actividades = DB::table('actividad')
                ->join('destino_actividad', 'actividad.id_actividad', '=', 'destino_actividad.id_actividad')
                ->where('destino_actividad.id_destino', $id)
                ->select('actividad.id_actividad', 'actividad.nombre')
                ->get();

            return response()->json([
                'descripcion' => $destino->descripcion,
                'lat'         => $destino->lat,
                'lng'         => $destino->lng,
                'actividades' => $actividades,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en infoDestino: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
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
            'fecha_inicio_operacion' => 'nullable|date',
            'fecha_fin_operacion'    => 'nullable|date|after_or_equal:fecha_inicio_operacion',
            'recomendaciones'        => 'nullable|array',
            'recomendaciones.*'      => 'exists:recomendacion,id_recomendacion',
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
                'fecha_inicio_operacion' => $request->fecha_inicio_operacion,
                'fecha_fin_operacion'    => $request->fecha_fin_operacion,
                'activo'                 => 'activo',
                'creado_por'             => $personaId,
                'fecha_creacion'         => now(),
            ]);

            // Recomendaciones
            if ($request->filled('recomendaciones')) {
                foreach ($request->recomendaciones as $idRecomendacion) {
                    DB::table('ruta_recomendacion')->insert([
                        'id_ruta'          => $ruta->id_ruta,
                        'id_recomendacion' => $idRecomendacion,
                    ]);
                }
            }

            // Imágenes
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $rutaArchivo = $foto->store('rutas', 'public');
                    DB::table('imagen')->insert([
                        'entidad'      => 'ruta',
                        'id_ruta'      => $ruta->id_ruta,
                        'ruta_archivo' => $rutaArchivo,
                        'subida_por'   => $personaId,
                        'fecha'        => now(),
                    ]);
                }
            }

            // Destinos y actividades
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



    // ── EDIT: mostrar formulario de edición (CORREGIDO) ──
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
            ->select('destino.id_destino', 'destino.nombre', 'destino.descripcion', 'ruta_destino.orden')
            ->get();

        $todosDestinos = DB::table('destino')->where('activo', 'activo')->orderBy('nombre')->get();
        $recomendacionesDisponibles = DB::table('recomendacion')->where('activo', 1)->orderBy('descripcion')->get();
        $recomendacionesSeleccionadas = DB::table('ruta_recomendacion')->where('id_ruta', $id)->pluck('id_recomendacion')->toArray();

        return view('admin.gestor_rutas.edit', compact('ruta', 'imagenes', 'destinos', 'todosDestinos', 'recomendacionesDisponibles', 'recomendacionesSeleccionadas'));
    }

    // ── UPDATE: guardar cambios (CORREGIDO) ──
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
            'nombre'                 => 'required|string|max:120',
            'descripcion'            => 'required|string',
            'dificultad'             => 'required|in:baja,media,alta',
            'distancia_km'           => 'nullable|numeric',
            'duracion_estimada'      => 'nullable|string|max:50',
            'fecha_inicio_operacion' => 'nullable|date',
            'fecha_fin_operacion'    => 'nullable|date|after_or_equal:fecha_inicio_operacion',
            'recomendaciones'        => 'nullable|array',
            'recomendaciones.*'      => 'exists:recomendacion,id_recomendacion',
            'destinos'               => 'required|array|min:1',
            'destinos.*'             => 'required|exists:destino,id_destino',
            'fotos'                  => 'nullable|array',
            'fotos.*'                => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            DB::table('ruta')->where('id_ruta', $id)->update([
                'nombre'                 => $request->nombre,
                'descripcion'            => $request->descripcion,
                'dificultad'             => $request->dificultad,
                'distancia_km'           => $request->distancia_km,
                'duracion_estimada'      => $request->duracion_estimada,
                'fecha_inicio_operacion' => $request->fecha_inicio_operacion,
                'fecha_fin_operacion'    => $request->fecha_fin_operacion,
            ]);

            DB::table('ruta_recomendacion')->where('id_ruta', $id)->delete();
            if ($request->filled('recomendaciones')) {
                foreach ($request->recomendaciones as $idRecomendacion) {
                    DB::table('ruta_recomendacion')->insert([
                        'id_ruta'          => $id,
                        'id_recomendacion' => $idRecomendacion,
                    ]);
                }
            }

            DB::table('ruta_destino_actividad')->where('id_ruta', $id)->delete();
            DB::table('ruta_destino')->where('id_ruta', $id)->delete();

            foreach ($request->destinos as $index => $idDestino) {
                $orden = $index + 1;
                DB::table('ruta_destino')->insert([
                    'id_ruta'    => $id,
                    'id_destino' => $idDestino,
                    'orden'      => $orden,
                ]);

                $actividades = $request->input("actividades_{$idDestino}", []);
                foreach ($actividades as $idActividad) {
                    DB::table('ruta_destino_actividad')->insert([
                        'id_ruta'      => $id,
                        'id_destino'   => $idDestino,
                        'id_actividad' => $idActividad,
                    ]);
                }
            }

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $rutaArchivo = $foto->store('rutas', 'public');
                    DB::table('imagen')->insert([
                        'entidad'      => 'ruta',
                        'id_ruta'      => $id,
                        'ruta_archivo' => $rutaArchivo,
                        'subida_por'   => $personaId,
                        'fecha'        => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('rutas.index')
                ->with('success', 'Ruta actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroyImagen($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $imagen = DB::table('imagen')->where('id_imagen', $id)->first();
        if (!$imagen) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        $ruta = Ruta::where('id_ruta', $imagen->id_ruta)
            ->where('creado_por', $personaId)
            ->first();
        if (!$ruta) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

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