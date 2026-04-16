<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDestinoController extends Controller
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
        $actividadesExistentes = DB::table('actividad')->orderBy('nombre')->get();
        $categorias            = DB::table('categoria')->orderBy('nombre')->get();
        return view('admin.destinos.create', compact('actividadesExistentes', 'categorias'));
    }

    public function store(Request $request)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $request->validate([
            'nombre'                            => ['required', 'max:120'],
            'descripcion'                       => ['required'],
            'telefono'                          => ['nullable', 'max:20'],
            'recomendaciones'                   => ['nullable'],
            'lat'                               => ['nullable', 'numeric'],
            'lng'                               => ['nullable', 'numeric'],
            'fotos'                             => ['nullable', 'array'],
            'fotos.*'                           => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'categorias'                        => ['nullable', 'array'],
            'categorias.*'                      => ['integer'],
            'actividades_existentes'            => ['nullable', 'array'],
            'actividades_existentes.*'          => ['integer'],
            'nuevas_actividades'                => ['nullable', 'array'],
            'nuevas_actividades.*.nombre'       => ['required_with:nuevas_actividades', 'max:80'],
            'nuevas_actividades.*.dificultad'   => ['required_with:nuevas_actividades', 'in:baja,media,alta'],
            'nuevas_actividades.*.duracion'     => ['nullable', 'max:50'],
            'nuevas_actividades.*.min_personas' => ['nullable', 'integer', 'min:1'],
            'nuevas_actividades.*.recomendacion'=> ['nullable'],
            'paquetes'                          => ['nullable', 'array'],
            'paquetes.*.nombre'                 => ['required_with:paquetes', 'max:120'],
            'paquetes.*.descripcion'            => ['nullable'],
            'paquetes.*.precio'                 => ['nullable', 'numeric', 'min:0'],
            'paquetes.*.minimo_personas'        => ['nullable', 'integer', 'min:1'],
        ]);

        // 1. Insertar destino
        $idDestino = DB::table('destino')->insertGetId([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'telefono'        => $request->telefono,
            'recomendaciones' => $request->recomendaciones,
            'lat'             => $request->lat,
            'lng'             => $request->lng,
            'activo'          => 'activo',
            'creado_por'      => $personaId,
            'fecha_creacion'  => now(),
        ]);

        // 2. Imágenes
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

        // 3. Categorías
        if ($request->filled('categorias')) {
            foreach ($request->categorias as $idCategoria) {
                DB::table('destino_categoria')->insertOrIgnore([
                    'id_destino'   => $idDestino,
                    'id_categoria' => $idCategoria,
                ]);
            }
        }

        // 4. Actividades existentes
        if ($request->filled('actividades_existentes')) {
            foreach ($request->actividades_existentes as $idActividad) {
                DB::table('destino_actividad')->insertOrIgnore([
                    'id_destino'   => $idDestino,
                    'id_actividad' => $idActividad,
                ]);
            }
        }

        // 5. Nuevas actividades
        if ($request->filled('nuevas_actividades')) {
            foreach ($request->nuevas_actividades as $act) {
                if (empty($act['nombre'])) continue;
                $actividad = DB::table('actividad')->where('nombre', $act['nombre'])->first();
                if ($actividad) {
                    $idActividad = $actividad->id_actividad;
                } else {
                    $idActividad = DB::table('actividad')->insertGetId([
                        'nombre'            => $act['nombre'],
                        'dificultad'        => $act['dificultad'] ?? 'baja',
                        'duracion_estimada' => $act['duracion'] ?? null,
                        'minimo_personas'   => $act['min_personas'] ?? null,
                        'recomendacion'     => $act['recomendacion'] ?? null,
                    ]);
                }
                DB::table('destino_actividad')->insertOrIgnore([
                    'id_destino'   => $idDestino,
                    'id_actividad' => $idActividad,
                ]);
            }
        }

        // 6. Paquetes
        if ($request->filled('paquetes')) {
            foreach ($request->paquetes as $paq) {
                if (empty($paq['nombre'])) continue;
                DB::table('paquete')->insert([
                    'id_destino'      => $idDestino,
                    'nombre'          => $paq['nombre'],
                    'descripcion'     => $paq['descripcion'] ?? null,
                    'precio'          => $paq['precio'] ?? null,
                    'minimo_personas' => $paq['minimo_personas'] ?? null,
                    'activo'          => 'activo',
                ]);
            }
        }

        return redirect()->route('misdestinos.index')
            ->with('success', 'Destino creado correctamente.');
    }

    public function edit($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $destino = DB::table('destino')
            ->where('id_destino', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$destino, 404);

        $imagenes              = DB::table('imagen')->where('entidad', 'destino')->where('id_destino', $id)->get();
        $categorias            = DB::table('categoria')->orderBy('nombre')->get();
        $actividadesExistentes = DB::table('actividad')->orderBy('nombre')->get();
        $actividadesDelDestino = DB::table('destino_actividad')->where('id_destino', $id)->pluck('id_actividad')->toArray();
        $categoriasDelDestino  = DB::table('destino_categoria')->where('id_destino', $id)->pluck('id_categoria')->toArray();
        $paquetes              = DB::table('paquete')->where('id_destino', $id)->get();

        return view('admin.destinos.edit', compact(
            'destino', 'imagenes', 'categorias', 'actividadesExistentes',
            'actividadesDelDestino', 'categoriasDelDestino', 'paquetes'
        ));
    }

    public function update(Request $request, $id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $destino = DB::table('destino')
            ->where('id_destino', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$destino, 404);

        $request->validate([
            'nombre'                            => ['required', 'max:120'],
            'descripcion'                       => ['required'],
            'telefono'                          => ['nullable', 'max:20'],
            'recomendaciones'                   => ['nullable'],
            'lat'                               => ['nullable', 'numeric'],
            'lng'                               => ['nullable', 'numeric'],
            'fotos'                             => ['nullable', 'array'],
            'fotos.*'                           => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'categorias'                        => ['nullable', 'array'],
            'categorias.*'                      => ['integer'],
            'actividades_existentes'            => ['nullable', 'array'],
            'actividades_existentes.*'          => ['integer'],
            'nuevas_actividades'                => ['nullable', 'array'],
            'nuevas_actividades.*.nombre'       => ['required_with:nuevas_actividades', 'max:80'],
            'nuevas_actividades.*.dificultad'   => ['required_with:nuevas_actividades', 'in:baja,media,alta'],
            'nuevas_actividades.*.duracion'     => ['nullable', 'max:50'],
            'nuevas_actividades.*.min_personas' => ['nullable', 'integer', 'min:1'],
            'nuevas_actividades.*.recomendacion'=> ['nullable'],
            'paquetes'                          => ['nullable', 'array'],
            'paquetes.*.nombre'                 => ['required_with:paquetes', 'max:120'],
            'paquetes.*.descripcion'            => ['nullable'],
            'paquetes.*.precio'                 => ['nullable', 'numeric', 'min:0'],
            'paquetes.*.minimo_personas'        => ['nullable', 'integer', 'min:1'],
        ]);

        DB::table('destino')->where('id_destino', $id)->update([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'telefono'        => $request->telefono,
            'recomendaciones' => $request->recomendaciones,
            'lat'             => $request->lat,
            'lng'             => $request->lng,
        ]);

        // Imágenes nuevas
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

        // Reemplazar categorías
        DB::table('destino_categoria')->where('id_destino', $id)->delete();
        if ($request->filled('categorias')) {
            foreach ($request->categorias as $idCategoria) {
                DB::table('destino_categoria')->insertOrIgnore([
                    'id_destino'   => $id,
                    'id_categoria' => $idCategoria,
                ]);
            }
        }

        // Reemplazar actividades
        DB::table('destino_actividad')->where('id_destino', $id)->delete();
        if ($request->filled('actividades_existentes')) {
            foreach ($request->actividades_existentes as $idActividad) {
                DB::table('destino_actividad')->insertOrIgnore([
                    'id_destino'   => $id,
                    'id_actividad' => $idActividad,
                ]);
            }
        }
        if ($request->filled('nuevas_actividades')) {
            foreach ($request->nuevas_actividades as $act) {
                if (empty($act['nombre'])) continue;
                $actividad = DB::table('actividad')->where('nombre', $act['nombre'])->first();
                if ($actividad) {
                    $idActividad = $actividad->id_actividad;
                } else {
                    $idActividad = DB::table('actividad')->insertGetId([
                        'nombre'            => $act['nombre'],
                        'dificultad'        => $act['dificultad'] ?? 'baja',
                        'duracion_estimada' => $act['duracion'] ?? null,
                        'minimo_personas'   => $act['min_personas'] ?? null,
                        'recomendacion'     => $act['recomendacion'] ?? null,
                    ]);
                }
                DB::table('destino_actividad')->insertOrIgnore([
                    'id_destino'   => $id,
                    'id_actividad' => $idActividad,
                ]);
            }
        }

        // Reemplazar paquetes
        DB::table('paquete')->where('id_destino', $id)->delete();
        if ($request->filled('paquetes')) {
            foreach ($request->paquetes as $paq) {
                if (empty($paq['nombre'])) continue;
                DB::table('paquete')->insert([
                    'id_destino'      => $id,
                    'nombre'          => $paq['nombre'],
                    'descripcion'     => $paq['descripcion'] ?? null,
                    'precio'          => $paq['precio'] ?? null,
                    'minimo_personas' => $paq['minimo_personas'] ?? null,
                    'activo'          => 'activo',
                ]);
            }
        }

        return redirect()->route('misdestinos.index')
            ->with('success', 'Destino actualizado correctamente.');
    }

    public function destroyImagen($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $imagen = DB::table('imagen')->where('id_imagen', $id)->first();
        abort_if(!$imagen, 404);

        $destino = DB::table('destino')
            ->where('id_destino', $imagen->id_destino)
            ->where('creado_por', $personaId)
            ->first();
        abort_if(!$destino, 403);

        Storage::disk('public')->delete($imagen->ruta_archivo);
        DB::table('imagen')->where('id_imagen', $id)->delete();

        return back()->with('success', 'Imagen eliminada.');
    }

    public function destroy($id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $destino = DB::table('destino')
            ->where('id_destino', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$destino, 404);

        $imagenes = DB::table('imagen')->where('entidad', 'destino')->where('id_destino', $id)->get();
        foreach ($imagenes as $img) {
            Storage::disk('public')->delete($img->ruta_archivo);
        }

        DB::table('imagen')->where('id_destino', $id)->delete();
        DB::table('destino_actividad')->where('id_destino', $id)->delete();
        DB::table('destino_categoria')->where('id_destino', $id)->delete();
        DB::table('paquete')->where('id_destino', $id)->delete();
        DB::table('destino')->where('id_destino', $id)->delete();

        return redirect()->route('misdestinos.index')
            ->with('success', 'Destino eliminado correctamente.');
    }

    // ── Suspender o reactivar un destino ──
    public function toggleActivo(Request $request, $id)
    {
        $personaId = $this->getPersonaId();
        if (!$personaId) {
            return redirect()->route('login')->with('error', 'Perfil no encontrado.');
        }

        $destino = DB::table('destino')
            ->where('id_destino', $id)
            ->where('creado_por', $personaId)
            ->first();

        abort_if(!$destino, 404);

        $nuevoEstado = $destino->activo === 'activo' ? 'inactivo' : 'activo';

        // 1. Cambiar el estado del destino
        DB::table('destino')
            ->where('id_destino', $id)
            ->update(['activo' => $nuevoEstado]);

        // 2. Buscar todas las rutas que contienen este destino
        $idsRutas = DB::table('ruta_destino')
            ->where('id_destino', $id)
            ->pluck('id_ruta');

        if ($idsRutas->isEmpty()) {
            return back()->with('success', 'Estado del destino actualizado.');
        }

        if ($nuevoEstado === 'inactivo') {

            // 3a. Si se suspendió: inhabilitar todas las rutas que lo contienen
            //     y guardar el motivo
            foreach ($idsRutas as $idRuta) {
                DB::table('ruta')->where('id_ruta', $idRuta)->update([
                    'activo'          => 'inactivo',
                    'motivo_inactivo' => 'El destino "' . $destino->nombre . '" se encuentra suspendido.',
                ]);
            }

            $mensaje = 'Destino suspendido. Las rutas que lo contienen han sido inhabilitadas.';

        } else {

            // 3b. Si se reactivó: revisar cada ruta afectada
            //     Solo reactivar si TODOS sus destinos están activos
            foreach ($idsRutas as $idRuta) {

                // Contar cuántos destinos de esta ruta están inactivos
                $destinosInactivos = DB::table('ruta_destino')
                    ->join('destino', 'ruta_destino.id_destino', '=', 'destino.id_destino')
                    ->where('ruta_destino.id_ruta', $idRuta)
                    ->where('destino.activo', 'inactivo')
                    ->count();

                if ($destinosInactivos === 0) {
                    // Todos los destinos están activos → reactivar la ruta
                    DB::table('ruta')->where('id_ruta', $idRuta)->update([
                        'activo'          => 'activo',
                        'motivo_inactivo' => null,
                    ]);
                }
            }

            $mensaje = 'Destino reactivado. Las rutas afectadas han sido revisadas.';
        }

        return back()->with('success', $mensaje);
    }
}