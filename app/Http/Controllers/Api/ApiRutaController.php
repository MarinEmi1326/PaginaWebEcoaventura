<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiRutaController extends Controller
{
    // GET /api/rutas
    public function index()
    {
        $rutas = DB::table('ruta')
            ->where('activo', 'activo')
            ->orderByDesc('fecha_creacion')
            ->get();

        foreach ($rutas as $ruta) {
            $ruta->total_paradas = DB::table('ruta_destino')
                ->where('id_ruta', $ruta->id_ruta)
                ->count();

            $imagen = DB::table('imagen')
                ->where('entidad', 'ruta')
                ->where('id_ruta', $ruta->id_ruta)
                ->orderBy('id_imagen')
                ->first();

            $ruta->imagen = $imagen ? $imagen->ruta_archivo : null;
        }

        return response()->json([
            'success' => true,
            'total'   => count($rutas),
            'data'    => $rutas,
        ]);
    }

    // GET /api/rutas/{id}
    public function show($id)
    {
        $ruta = DB::table('ruta')
            ->where('id_ruta', $id)
            ->where('activo', 'activo')
            ->first();

        if (!$ruta) {
            return response()->json(['success' => false, 'message' => 'Ruta no encontrada'], 404);
        }

        // Imagenes
        $ruta->imagenes = DB::table('imagen')
            ->where('entidad', 'ruta')
            ->where('id_ruta', $id)
            ->get();

        // Destinos en orden
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

        // ✅ CORREGIDO: Actividades - solo nombre (dificultad y duración están en ruta, no en actividad)
        foreach ($destinos as $destino) {
            $destino->actividades = DB::table('actividad')
                ->join('ruta_destino_actividad', 'actividad.id_actividad', '=', 'ruta_destino_actividad.id_actividad')
                ->where('ruta_destino_actividad.id_ruta', $id)
                ->where('ruta_destino_actividad.id_destino', $destino->id_destino)
                ->select('actividad.id_actividad', 'actividad.nombre') // ✅ Solo nombre
                ->get();
        }

        $ruta->destinos = $destinos;

        // Comentarios usando persona
        $ruta->comentarios = DB::table('comentario')
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

        return response()->json(['success' => true, 'data' => $ruta]);
    }
}