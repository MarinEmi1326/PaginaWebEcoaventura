<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDestinoController extends Controller
{
    private function getPersonaId()
    {
        $persona = DB::table('persona')->where('id_usuario', Auth::id())->first();
        return $persona ? $persona->id_persona : null;
    }

    public function index()
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) return redirect()->route('login')->with('error', 'Perfil no encontrado.');

        $destinos = DB::table('destino')
            ->where('creado_por', $personaId)
            ->orderByDesc('fecha_creacion')
            ->get();

        foreach ($destinos as $destino) {
            $imagen = DB::table('imagen')
                ->where('entidad', 'destino')
                ->where('id_destino', $destino->id_destino)
                ->orderBy('id_imagen')
                ->first();
            $destino->imagen = $imagen ? $imagen->ruta_archivo : null;
        }

        $total     = $destinos->count();
        $aprobados = $destinos->where('activo', 'activo')->count();
        $inactivos = $destinos->where('activo', 'inactivo')->count();

        return view('admin.destinos.index', compact('destinos', 'total', 'aprobados', 'inactivos'));
    }

    public function create()
    {
        $actividades   = DB::table('actividad')->orderBy('nombre')->get();
        $categorias    = DB::table('categoria')->orderBy('nombre')->get();
        $recomendaciones = DB::table('recomendacion')->where('activo', 1)->orderBy('descripcion')->get();
        return view('admin.destinos.create', compact('actividades', 'categorias', 'recomendaciones'));
    }

    public function store(Request $request)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) return redirect()->route('login')->with('error', 'Perfil no encontrado.');

        $request->validate([
            'nombre'          => 'required|max:120',
            'descripcion'     => 'required',
            'telefono'        => 'nullable|max:20',
            'lat'             => 'nullable|numeric',
            'lng'             => 'nullable|numeric',
            'fotos'           => 'nullable|array',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png|max:5120',
            'categorias'      => 'nullable|array',
            'categorias.*'    => 'integer',
            'actividades'     => 'nullable|array',
            'actividades.*'   => 'integer',
            'recomendaciones' => 'nullable|array',
            'recomendaciones.*' => 'integer',
        ]);

        DB::beginTransaction();
        try {
            // Destino
            $idDestino = DB::table('destino')->insertGetId([
                'nombre'         => $request->nombre,
                'descripcion'    => $request->descripcion,
                'telefono'       => $request->telefono,
                'lat'            => $request->lat,
                'lng'            => $request->lng,
                'activo'         => 'activo',
                'creado_por'     => $personaId,
                'fecha_creacion' => now(),
            ]);

            // Imágenes
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $ruta = $foto->store('destinos', 'public');
                    DB::table('imagen')->insert([
                        'entidad'      => 'destino',
                        'id_destino'   => $idDestino,
                        'ruta_archivo' => $ruta,
                        'subida_por'   => $personaId,
                        'fecha'        => now(),
                    ]);
                }
            }

            // Categorías
            if ($request->filled('categorias')) {
                foreach ($request->categorias as $catId) {
                    DB::table('destino_categoria')->insertOrIgnore(['id_destino' => $idDestino, 'id_categoria' => $catId]);
                }
            }

            // Actividades (solo selección)
            if ($request->filled('actividades')) {
                foreach ($request->actividades as $actId) {
                    DB::table('destino_actividad')->insertOrIgnore(['id_destino' => $idDestino, 'id_actividad' => $actId]);
                }
            }

            // Recomendaciones
            if ($request->filled('recomendaciones')) {
                foreach ($request->recomendaciones as $recId) {
                    DB::table('destino_recomendacion')->insertOrIgnore(['id_destino' => $idDestino, 'id_recomendacion' => $recId]);
                }
            }

            DB::commit();
            return redirect()->route('misdestinos.index')->with('success', 'Destino creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) abort(404);

        $destino = DB::table('destino')->where('id_destino', $id)->where('creado_por', $personaId)->first();
        abort_if(!$destino, 404);

        $imagenes   = DB::table('imagen')->where('entidad', 'destino')->where('id_destino', $id)->get();
        $categorias = DB::table('categoria')->orderBy('nombre')->get();
        $actividades = DB::table('actividad')->orderBy('nombre')->get();
        $recomendaciones = DB::table('recomendacion')->where('activo', 1)->orderBy('descripcion')->get();

        $actividadesDelDestino = DB::table('destino_actividad')->where('id_destino', $id)->pluck('id_actividad')->toArray();
        $categoriasDelDestino  = DB::table('destino_categoria')->where('id_destino', $id)->pluck('id_categoria')->toArray();
        $recomendacionesDelDestino = DB::table('destino_recomendacion')->where('id_destino', $id)->pluck('id_recomendacion')->toArray();

        return view('admin.destinos.edit', compact('destino', 'imagenes', 'categorias', 'actividades', 'recomendaciones',
            'actividadesDelDestino', 'categoriasDelDestino', 'recomendacionesDelDestino'));
    }

    public function update(Request $request, $id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) abort(404);

        $destino = DB::table('destino')->where('id_destino', $id)->where('creado_por', $personaId)->first();
        abort_if(!$destino, 404);

        $request->validate([
            'nombre'          => 'required|max:120',
            'descripcion'     => 'required',
            'telefono'        => 'nullable|max:20',
            'lat'             => 'nullable|numeric',
            'lng'             => 'nullable|numeric',
            'fotos'           => 'nullable|array',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png|max:5120',
            'categorias'      => 'nullable|array',
            'categorias.*'    => 'integer',
            'actividades'     => 'nullable|array',
            'actividades.*'   => 'integer',
            'recomendaciones' => 'nullable|array',
            'recomendaciones.*' => 'integer',
        ]);

        DB::beginTransaction();
        try {
            DB::table('destino')->where('id_destino', $id)->update([
                'nombre'      => $request->nombre,
                'descripcion' => $request->descripcion,
                'telefono'    => $request->telefono,
                'lat'         => $request->lat,
                'lng'         => $request->lng,
            ]);

            // Nuevas imágenes
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $ruta = $foto->store('destinos', 'public');
                    DB::table('imagen')->insert([
                        'entidad'      => 'destino',
                        'id_destino'   => $id,
                        'ruta_archivo' => $ruta,
                        'subida_por'   => $personaId,
                        'fecha'        => now(),
                    ]);
                }
            }

            // Sincronizar categorías
            DB::table('destino_categoria')->where('id_destino', $id)->delete();
            if ($request->filled('categorias')) {
                foreach ($request->categorias as $catId) {
                    DB::table('destino_categoria')->insert(['id_destino' => $id, 'id_categoria' => $catId]);
                }
            }

            // Sincronizar actividades
            DB::table('destino_actividad')->where('id_destino', $id)->delete();
            if ($request->filled('actividades')) {
                foreach ($request->actividades as $actId) {
                    DB::table('destino_actividad')->insert(['id_destino' => $id, 'id_actividad' => $actId]);
                }
            }

            // Sincronizar recomendaciones
            DB::table('destino_recomendacion')->where('id_destino', $id)->delete();
            if ($request->filled('recomendaciones')) {
                foreach ($request->recomendaciones as $recId) {
                    DB::table('destino_recomendacion')->insert(['id_destino' => $id, 'id_recomendacion' => $recId]);
                }
            }

            DB::commit();
            return redirect()->route('misdestinos.index')->with('success', 'Destino actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroyImagen($id)
    {
        $personaId = $this->getPersonaId();
        $imagen = DB::table('imagen')->where('id_imagen', $id)->first();
        abort_if(!$imagen, 404);
        $destino = DB::table('destino')->where('id_destino', $imagen->id_destino)->where('creado_por', $personaId)->first();
        abort_if(!$destino, 403);
        Storage::disk('public')->delete($imagen->ruta_archivo);
        DB::table('imagen')->where('id_imagen', $id)->delete();
        return back()->with('success', 'Imagen eliminada.');
    }

    public function destroy($id)
    {
        $personaId = $this->getPersonaId();
        $destino = DB::table('destino')->where('id_destino', $id)->where('creado_por', $personaId)->first();
        abort_if(!$destino, 404);
        $imagenes = DB::table('imagen')->where('entidad', 'destino')->where('id_destino', $id)->get();
        foreach ($imagenes as $img) Storage::disk('public')->delete($img->ruta_archivo);
        DB::table('imagen')->where('id_destino', $id)->delete();
        DB::table('destino_actividad')->where('id_destino', $id)->delete();
        DB::table('destino_categoria')->where('id_destino', $id)->delete();
        DB::table('destino_recomendacion')->where('id_destino', $id)->delete();
        DB::table('destino')->where('id_destino', $id)->delete();
        return redirect()->route('misdestinos.index')->with('success', 'Destino eliminado.');
    }

    public function toggleActivo(Request $request, $id)
    {
        $personaId = $this->getPersonaId();
        $destino = DB::table('destino')->where('id_destino', $id)->where('creado_por', $personaId)->first();
        abort_if(!$destino, 404);
        $nuevoEstado = $destino->activo === 'activo' ? 'inactivo' : 'activo';
        DB::table('destino')->where('id_destino', $id)->update(['activo' => $nuevoEstado]);

        // Actualizar rutas asociadas (igual que antes)
        $idsRutas = DB::table('ruta_destino')->where('id_destino', $id)->pluck('id_ruta');
        if ($idsRutas->isNotEmpty()) {
            if ($nuevoEstado === 'inactivo') {
                foreach ($idsRutas as $idRuta) {
                    DB::table('ruta')->where('id_ruta', $idRuta)->update([
                        'activo' => 'inactivo',
                        'motivo_inactivo' => 'El destino "' . $destino->nombre . '" se encuentra suspendido.',
                    ]);
                }
                $mensaje = 'Destino suspendido. Las rutas que lo contienen han sido inhabilitadas.';
            } else {
                foreach ($idsRutas as $idRuta) {
                    $destinosInactivos = DB::table('ruta_destino')
                        ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
                        ->where('ruta_destino.id_ruta', $idRuta)
                        ->where('destino.activo', 'inactivo')
                        ->count();
                    if ($destinosInactivos === 0) {
                        DB::table('ruta')->where('id_ruta', $idRuta)->update(['activo' => 'activo', 'motivo_inactivo' => null]);
                    }
                }
                $mensaje = 'Destino reactivado. Las rutas afectadas han sido revisadas.';
            }
            return back()->with('success', $mensaje);
        }
        return back()->with('success', 'Estado del destino actualizado.');
    }
}