<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::all();
        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        return view('rutas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:120',
            'descripcion'      => 'required|string',
            'punto_inicio_lat' => 'required|numeric',
            'punto_inicio_lng' => 'required|numeric',
            'dificultad'       => 'required|in:baja,media,alta',
            'distancia_km'     => 'nullable|numeric',
            'duracion_estimada'=> 'nullable|string|max:50',
            'recomendaciones'  => 'nullable|string',
        ]);

        Ruta::create([
            'nombre'            => $request->nombre,
            'descripcion'       => $request->descripcion,
            'punto_inicio_lat'  => $request->punto_inicio_lat,
            'punto_inicio_lng'  => $request->punto_inicio_lng,
            'dificultad'        => $request->dificultad,
            'distancia_km'      => $request->distancia_km,
            'duracion_estimada' => $request->duracion_estimada,
            'recomendaciones'   => $request->recomendaciones,
            'activo'            => 'activo',
            'creado_por'        => 1, // reemplazar con auth()->id() cuando tengas login
        ]);

        return redirect()->route('rutas.index')
                         ->with('success', 'Ruta guardada correctamente.');
    }
}