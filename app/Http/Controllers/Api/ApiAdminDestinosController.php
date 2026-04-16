<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAdminDestinosController extends Controller
{
    // Helper para obtener la persona del usuario autenticado
    private function getPersona($userId)
    {
        return DB::table('persona')->where('id_usuario', $userId)->first();
    }

    // Helper para verificar si el usuario tiene un rol específico
    private function tieneRol($userId, $rolNombre)
    {
        $persona = $this->getPersona($userId);
        if (!$persona) return false;

        $rol = DB::table('persona_rol')
            ->join('rol', 'persona_rol.id_rol', '=', 'rol.id_rol')
            ->where('persona_rol.id_persona', $persona->id_persona)
            ->where('rol.descripcion', $rolNombre)
            ->first();

        return $rol !== null;
    }

    // GET /api/admin/dashboard
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // CAMBIADO: verificar rol desde persona_rol
        if (!$this->tieneRol($user->id_usuario, 'admin_destinos')) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        // CAMBIADO: obtener la persona
        $persona = $this->getPersona($user->id_usuario);

        // Total de destinos publicados (creado_por ahora es id_persona)
        $totalDestinos = DB::table('destino')
            ->where('creado_por', $persona->id_persona)
            ->count();

        // Total de pagos recibidos
        $totalPagos = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('destino.creado_por', $persona->id_persona)
            ->where('pago.estado', 'completado')
            ->count();

        // Total ingresos
        $totalIngresos = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('destino.creado_por', $persona->id_persona)
            ->where('pago.estado', 'completado')
            ->sum('pago.monto');

        return response()->json([
            'success' => true,
            'data' => [
                'nombre'         => $persona->nombre ?? '',
                'apellidos'      => $persona->apellidos ?? '',  
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

        // CAMBIADO: verificar rol desde persona_rol
        if (!$this->tieneRol($user->id_usuario, 'admin_destinos')) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        // CAMBIADO: obtener la persona
        $persona = $this->getPersona($user->id_usuario);

        // CAMBIADO: creado_por ahora es id_persona
        $destinos = DB::table('destino')
            ->where('creado_por', $persona->id_persona)
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

        // CAMBIADO: verificar rol desde persona_rol
        if (!$this->tieneRol($user->id_usuario, 'admin_destinos')) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        // CAMBIADO: obtener la persona
        $persona = $this->getPersona($user->id_usuario);

        // CAMBIADO: usar persona en lugar de turista
        $pagos = DB::table('pago')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('persona', 'pago.id_persona', '=', 'persona.id_persona')  // CAMBIADO: turista → persona
            ->where('destino.creado_por', $persona->id_persona)
            ->select(
                'pago.id_pago',
                'pago.monto',
                'pago.estado',
                'pago.fecha',
                'pago.moneda',
                'destino.nombre as destino_nombre',
                'paquete.nombre as paquete_nombre',
                'persona.nombre as persona_nombre',        // CAMBIADO: turista_nombre → persona_nombre
                'persona.apellidos as persona_apellidos'   // CAMBIADO: turista_apaterno → persona_apellidos
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