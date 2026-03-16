<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAdminDestinosController extends Controller
{
    // GET /api/admin/dashboard
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->rol !== 'admin_destinos') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $admin = DB::table('admin_destinos')->where('id_usuario', $user->id_usuario)->first();

        // Total de destinos publicados
        $totalDestinos = DB::table('destino')
            ->where('creado_por', $user->id_usuario)
            ->count();

        // Total de pagos recibidos
        $totalPagos = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('destino.creado_por', $user->id_usuario)
            ->where('pago.estado', 'completado')
            ->count();

        // Total ingresos
        $totalIngresos = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('destino.creado_por', $user->id_usuario)
            ->where('pago.estado', 'completado')
            ->sum('pago.monto');

        return response()->json([
            'success' => true,
            'data' => [
                'nombre'         => $admin->nombre ?? '',
                'apaterno'       => $admin->apaterno ?? '',
                'totalDestinos'  => $totalDestinos,
                'totalPagos'     => $totalPagos,
                'totalIngresos'  => $totalIngresos,
            ],
        ]);
    }

    // GET /api/admin/destinos
    public function misDestinos(Request $request)
    {
        $user = $request->user();

        if ($user->rol !== 'admin_destinos') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $destinos = DB::table('destino')
            ->where('creado_por', $user->id_usuario)
            ->orderByDesc('fecha_creacion')
            ->get();

        foreach ($destinos as $d) {
            $d->imagenes = DB::table('imagen')
                ->where('id_destino', $d->id_destino)
                ->where('entidad', 'destino')
                ->get();

            $d->categorias = DB::table('categoria')
                ->join('destino_categoria', 'categoria.id_categoria', '=', 'destino_categoria.id_categoria')
                ->where('destino_categoria.id_destino', $d->id_destino)
                ->select('categoria.*')
                ->get();

            $d->total_pagos = DB::table('pago')
                ->where('id_destino', $d->id_destino)
                ->where('estado', 'completado')
                ->count();
        }

        return response()->json([
            'success' => true,
            'total'   => count($destinos),
            'data'    => $destinos,
        ]);
    }

    // GET /api/admin/pagos
    public function pagos(Request $request)
    {
        $user = $request->user();

        if ($user->rol !== 'admin_destinos') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $pagos = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('turista', 'pago.id_turista', '=', 'turista.id_turista')
            ->where('destino.creado_por', $user->id_usuario)
            ->select(
                'pago.id_pago',
                'pago.monto',
                'pago.estado',
                'pago.fecha',
                'pago.moneda',
                'destino.nombre as destino_nombre',
                'paquete.nombre as paquete_nombre',
                'turista.nombre as turista_nombre',
                'turista.apaterno as turista_apaterno'
            )
            ->orderByDesc('pago.fecha')
            ->get();

        return response()->json([
            'success' => true,
            'total'   => count($pagos),
            'data'    => $pagos,
        ]);
    }
}