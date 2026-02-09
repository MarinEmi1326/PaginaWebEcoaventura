<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RechazoSolicitudMail;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudAprobadaMail;

class AdminSolicitudesController extends Controller
{
    public function index()
    {
        $solicitudes = DB::table('usuario as u')
            ->leftJoin('hotelero as h', 'h.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('restaurantero as r', 'r.id_usuario', '=', 'u.id_usuario')
            ->whereIn('u.rol', ['hotelero', 'restaurantero'])
            ->where('u.estado', 'pendiente')
            ->select(
                'u.id_usuario',
                'u.correo',
                'u.rol',
                'u.activo',
                'u.estado',
                'u.fecha_solicitud',
                DB::raw("COALESCE(h.nombre, r.nombre) as nombre"),
                DB::raw("COALESCE(h.apaterno, r.apaterno) as apaterno"),
                DB::raw("COALESCE(h.amaterno, r.amaterno) as amaterno"),
                DB::raw("COALESCE(h.telefono, r.telefono) as telefono")
            )
            ->orderByDesc('u.fecha_solicitud')
            ->get();

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

   public function show($id)
{
    $solicitud = DB::table('usuario as u')
        ->leftJoin('hotelero as h', 'h.id_usuario', '=', 'u.id_usuario')
        ->leftJoin('restaurantero as r', 'r.id_usuario', '=', 'u.id_usuario')
        ->where('u.id_usuario', $id)
        ->whereIn('u.rol', ['hotelero', 'restaurantero'])
        ->select(
            'u.id_usuario',
            'u.correo',
            'u.rol',
            'u.activo',
            'u.estado',
            'u.fecha_solicitud',
            'u.fecha_respuesta',
            'u.motivo_rechazo',
            DB::raw("COALESCE(h.nombre, r.nombre) as nombre"),
            DB::raw("COALESCE(h.apaterno, r.apaterno) as apaterno"),
            DB::raw("COALESCE(h.amaterno, r.amaterno) as amaterno"),
            DB::raw("COALESCE(h.telefono, r.telefono) as telefono"),
            // Datos del negocio (hotel/restaurante) si ya se crearon:
            'h.id_hotelero',
            'r.id_restaurantero'
        )
        ->first();

    abort_if(!$solicitud, 404);

    // Traer hotel/restaurante asociado (si existe)
    $hotel = null;
    $restaurante = null;

    if ($solicitud->rol === 'hotelero') {
        $hotel = DB::table('hotel')
            ->where('id_hotelero', $solicitud->id_hotelero)
            ->select('nombre', 'direccion', 'telefono', 'foto')
            ->first();
    }

    if ($solicitud->rol === 'restaurantero') {
        $restaurante = DB::table('restaurante')
            ->where('id_restaurantero', $solicitud->id_restaurantero)
            ->select('nombre', 'direccion', 'telefono', 'foto')
            ->first();
    }

    return view('admin.solicitudes.show', compact('solicitud', 'hotel', 'restaurante'));
}





    public function aprobar($id)
    {
        // 2. Buscamos los datos básicos del usuario y su nombre real
        $u = DB::table('usuario as u')
            ->leftJoin('hotelero as h', 'h.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('restaurantero as r', 'r.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->select('u.*', DB::raw("COALESCE(h.nombre, r.nombre) as nombre_persona"))
            ->first();

        abort_if(!$u, 404);

        if ($u->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        // 3. Actualizamos en la base de datos
        DB::table('usuario')->where('id_usuario', $id)->update([
            'estado' => 'aprobado',
            'activo' => 1,
            'fecha_respuesta' => now(),
            'motivo_rechazo' => null,
        ]);

        // 4. Enviamos el correo de aprobación
        try {
            Mail::to($u->correo)->send(new SolicitudAprobadaMail($u));
        } catch (\Exception $e) {
            dd($e->getMessage());// Opcional: Loggear el error si el mail falla pero la aprobación sigue
        }

        return redirect()->route('admin.solicitudes.index')->with('ok', 'Solicitud aprobada correctamente y correo de bienvenida enviado.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => ['required', 'min:5'],
        ]);

        // Buscamos los datos incluyendo el nombre
        $u = DB::table('usuario as u')
            ->leftJoin('hotelero as h', 'h.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('restaurantero as r', 'r.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->select('u.*', DB::raw("COALESCE(h.nombre, r.nombre) as nombre_persona"))
            ->first();

        abort_if(!$u, 404);

        if ($u->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        DB::table('usuario')->where('id_usuario', $id)->update([
            'estado' => 'rechazado',
            'activo' => 0,
            'fecha_respuesta' => now(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        // 5. Enviamos correo de rechazo usando el nombre recuperado
        try {
            Mail::to($u->correo)->send(new RechazoSolicitudMail($request->motivo_rechazo, $u->nombre_persona));
        } catch (\Exception $e) {
           dd("Error al enviar el correo: " . $e->getMessage()); // Error silencioso de mail
        }

       return redirect()->route('admin.solicitudes.index')->with('ok', 'Solicitud rechazada correctamente y notificación enviada.');
    }

}
