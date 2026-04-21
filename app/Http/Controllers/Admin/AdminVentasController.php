<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVentasController extends Controller
{
    public function index()
    {
        $persona = auth()->user()->persona;

        // Ventas de los destinos del admin
        $ventas = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('persona', 'pago.id_persona', '=', 'persona.id_persona')
            ->where('destino.creado_por', $persona->id_persona)
            ->where('pago.estado', 'completado')
            ->select(
                'pago.*',
                'destino.nombre as destino_nombre',
                'paquete.nombre as paquete_nombre',
                'persona.nombre as persona_nombre',
                'persona.apellidos as persona_apellidos'
            )
            ->orderByDesc('pago.fecha')
            ->get();

        $ventasTotales = $ventas->count();
        $ingresosTotales = $ventas->sum('monto');
        $ticketPromedio = $ventasTotales > 0 ? $ingresosTotales / $ventasTotales : 0;

        return view('admin.ventas.ventas', compact('ventas', 'ventasTotales', 'ingresosTotales', 'ticketPromedio'));
    }
}
