<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RechazoSolicitudMail;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SolicitudAprobadaMail;

class AdminSolicitudesController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('usuario as u')
            ->leftJoin('hotelero as h', 'h.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('restaurantero as r', 'r.id_usuario', '=', 'u.id_usuario')
            ->whereIn('u.rol', ['hotelero', 'restaurantero']);

        // Filtro opcional: si no se pide un estado, podrías mostrar todos
        // o por defecto los pendientes, pero permitiendo ver los demás.
        if ($request->has('estado') && $request->estado != 'todos') {
            $query->where('u.estado', $request->estado);
        }

        $solicitudes = $query->select(
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


    public function create() {
    return view('admin.solicitudes.create');
    }

    public function store(Request $request) {
        $request->validate([
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8',
            'rol' => 'required|in:hotelero,restaurantero',
            'nombre' => 'required|string',
            'apaterno' => 'required|string',
            'telefono' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $idUsuario = DB::table('usuario')->insertGetId([
                'correo' => $request->correo,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
                'estado' => 'aprobado', // Se crea ya aprobado por el admin
                'activo' => 1,
                'fecha_solicitud' => now(),
                'fecha_respuesta' => now(),
            ]);

            $tabla = ($request->rol === 'hotelero') ? 'hotelero' : 'restaurantero';
            
            DB::table($tabla)->insert([
                'nombre' => $request->nombre,
                'apaterno' => $request->apaterno,
                'amaterno' => $request->amaterno,
                'telefono' => $request->telefono,
                'id_usuario' => $idUsuario
            ]);
        });

        return redirect()->route('admin.solicitudes.index')->with('ok', 'Usuario creado exitosamente');
    }

    public function edit($id)
    {
        $usuario = DB::table('usuario as u')
            ->leftJoin('hotelero as h', 'h.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('restaurantero as r', 'r.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->select(
                'u.*', 
                DB::raw("COALESCE(h.nombre, r.nombre) as nombre"),
                DB::raw("COALESCE(h.apaterno, r.apaterno) as apaterno"),
                DB::raw("COALESCE(h.amaterno, r.amaterno) as amaterno"),
                DB::raw("COALESCE(h.telefono, r.telefono) as telefono")
            )
            ->first();

        if (!$usuario) return redirect()->route('admin.solicitudes.index')->with('error', 'Usuario no encontrado');

        return view('admin.solicitudes.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'correo' => 'required|email',
            'nombre' => 'required|string',
            'apaterno' => 'required|string',
            'rol' => 'required',
            'telefono' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request, $id) {
            // 1. Actualizar tabla Usuario
            $datosUsuario = [
                'correo' => $request->correo,
                'rol' => $request->rol,
            ];

            // Solo actualizar contraseña si se escribió algo en el campo
            if ($request->filled('password')) {
                $datosUsuario['password'] = Hash::make($request->password);
            }

            DB::table('usuario')->where('id_usuario', $id)->update($datosUsuario);

            // 2. Actualizar tabla de perfil según el rol
            $tablaPerfil = ($request->rol === 'hotelero') ? 'hotelero' : 'restaurantero';
            
            DB::table($tablaPerfil)->where('id_usuario', $id)->update([
                'nombre' => $request->nombre,
                'apaterno' => $request->apaterno,
                'amaterno' => $request->amaterno,
                'telefono' => $request->telefono,
            ]);
        });

        return redirect()->route('admin.solicitudes.index')->with('ok', 'Usuario actualizado correctamente');
    }

    public function toggleActivo($id)
    {
        $usuario = DB::table('usuario')->where('id_usuario', $id)->first();

        if (!$usuario) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        // Cambiamos el estado al opuesto
        $nuevoEstado = $usuario->activo ? 0 : 1;
        $mensaje = $nuevoEstado ? 'Usuario habilitado con éxito.' : 'Usuario suspendido correctamente.';

        DB::table('usuario')->where('id_usuario', $id)->update([
            'activo' => $nuevoEstado
        ]);

        return back()->with('ok', $mensaje);
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
