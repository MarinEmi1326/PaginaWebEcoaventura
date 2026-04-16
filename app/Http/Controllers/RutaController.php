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
        $rutas = DB::table('ruta')
            ->where('activo', 'activo')
            ->orderByDesc('fecha_creacion')
            ->get();

        foreach ($rutas as $ruta) {
            // Contar paradas
            $ruta->total_paradas = DB::table('ruta_destino')
                ->where('id_ruta', $ruta->id_ruta)
                ->count();

            // Traer la primera imagen de la ruta si existe
            $imagen = DB::table('imagen')
                ->where('entidad', 'ruta')
                ->where('id_ruta', $ruta->id_ruta)
                ->orderBy('id_imagen')
                ->first();

            $ruta->imagen = $imagen ? $imagen->ruta_archivo : null;
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
    // Devuelve descripción y actividades de un destino en formato JSON
    public function infoDestino($id)
    {
        $destino = DB::table('destino')->where('id_destino', $id)->first();

        if (!$destino) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        // Buscamos las actividades del destino con un JOIN
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

        // Usamos transacción: si algo falla a la mitad, no queda nada a medias
        DB::transaction(function () use ($request, $personaId) {

            // 1. Guardar la ruta
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

            // 2. Guardar destinos con orden
            foreach ($request->destinos as $index => $idDestino) {

                DB::table('ruta_destino')->insert([
                    'id_ruta'    => $ruta->id_ruta,
                    'id_destino' => $idDestino,
                    'orden'      => $index + 1,
                ]);

                // 3. Guardar actividades seleccionadas para este destino
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

        // 2. Traer los destinos de la ruta en orden con sus actividades
        $destinos = DB::table('ruta_destino')
            ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
            ->where('ruta_destino.id_ruta', $id)
            ->orderBy('ruta_destino.orden')
            ->select(
                'destino.id_destino',
                'destino.nombre',
                'destino.descripcion',
                'destino.recomendaciones',
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
                ->select('actividad.nombre', 'actividad.dificultad', 'actividad.duracion_estimada')
                ->get();
        }

        // 4. Traer comentarios de la ruta (ahora usando persona en lugar de turista)
        $comentarios = DB::table('comentario')
            ->join('persona', 'comentario.id_persona', '=', 'persona.id_persona')
            ->where('comentario.entidad', 'ruta')
            ->where('comentario.id_ruta', $id)
            ->orderByDesc('comentario.fecha')
            ->select(
                'comentario.id_comentario',
                'comentario.comentario',
                'comentario.fecha',
                'persona.nombre',
                'persona.apellidos'
            )
            ->get();

        return view('rutas.show', compact('ruta', 'destinos', 'comentarios'));
    }

    // ── EDIT: mostrar formulario de edición ──
    public function edit($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        // Verificar que la ruta existe y pertenece al gestor autenticado
        $ruta = Ruta::where('id_ruta', $id)
                    ->where('creado_por', $personaId)
                    ->first();

        abort_if(!$ruta, 404);

        // Traer imágenes actuales de la ruta
        $imagenes = DB::table('imagen')
            ->where('entidad', 'ruta')
            ->where('id_ruta', $id)
            ->get();

        // Traer destinos de la ruta en orden (solo para mostrarlos, no editarlos)
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

        // Actualizar solo los campos editables
        DB::table('ruta')
            ->where('id_ruta', $id)
            ->update([
                'nombre'          => $request->nombre,
                'descripcion'     => $request->descripcion,
                'recomendaciones' => $request->recomendaciones,
            ]);

        // Agregar nuevas imágenes si se subieron
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

        // Verificar que la ruta pertenece al gestor autenticado
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

        // 1. Eliminar imágenes del storage físico y de la tabla imagen
        $imagenes = DB::table('imagen')
            ->where('entidad', 'ruta')
            ->where('id_ruta', $id)
            ->get();

        foreach ($imagenes as $img) {
            Storage::disk('public')->delete($img->ruta_archivo);
        }
        DB::table('imagen')->where('id_ruta', $id)->delete();

        // 2. Eliminar solo las relaciones de esta ruta
        DB::table('ruta_destino_actividad')->where('id_ruta', $id)->delete();
        DB::table('ruta_destino')->where('id_ruta', $id)->delete();

        // 3. Eliminar la ruta
        $ruta->delete();

        return redirect()->route('rutas.index')
                        ->with('success', 'Ruta eliminada correctamente.');
    }
}