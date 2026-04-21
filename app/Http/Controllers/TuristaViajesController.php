<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TuristaViajesController extends Controller
{
    public function index()
    {
        $persona = auth()->user()->persona;

        $viajes = DB::table('pago')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('pago.id_persona', $persona->id_persona)
            ->where('pago.estado', 'completado')
            ->select(
                'pago.*',
                'paquete.nombre as paquete_nombre',
                'destino.nombre as destino_nombre'
            )
            ->orderByDesc('pago.fecha')
            ->get();

        return view('turista.viajes.index', compact('viajes'));
    }

    public function show($id)
    {
        $persona = auth()->user()->persona;

        $viaje = DB::table('pago')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('pago.id_pago', $id)
            ->where('pago.id_persona', $persona->id_persona)
            ->select(
                'pago.*',
                'paquete.nombre as paquete_nombre',
                'paquete.descripcion as paquete_descripcion',
                'destino.nombre as destino_nombre',
                'destino.descripcion as destino_descripcion',
                'destino.lat',
                'destino.lng'
            )
            ->first();

        if (!$viaje) abort(404);

        return view('turista.viajes.show', compact('viaje'));
    }


    public function imprimir($id)
    {
        $persona = auth()->user()->persona;

        $viaje = DB::table('pago')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('pago.id_pago', $id)
            ->where('pago.id_persona', $persona->id_persona)
            ->select(
                'pago.*',
                'paquete.nombre as paquete_nombre',
                'destino.nombre as destino_nombre'
            )
            ->first();

        if (!$viaje) abort(404);

        return view('turista.viajes.imprimir', compact('viaje'));
    }
}
