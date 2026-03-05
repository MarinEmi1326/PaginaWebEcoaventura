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
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->whereIn('u.rol', ['admin_destinos', 'gestor_rutas']);

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
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre"),
                DB::raw("COALESCE(ad.apaterno, gr.apaterno) as apaterno"),
                DB::raw("COALESCE(ad.amaterno, gr.amaterno) as amaterno"),
                DB::raw("COALESCE(ad.telefono, gr.telefono) as telefono")
            )
            ->orderByDesc('u.fecha_solicitud')
            ->get();

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    public function show($id)
    {
        $solicitud = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->whereIn('u.rol', ['admin_destinos', 'gestor_rutas'])
            ->select(
                'u.id_usuario',
                'u.correo',
                'u.rol',
                'u.activo',
                'u.estado',
                'u.fecha_solicitud',
                'u.fecha_respuesta',
                'u.motivo_rechazo',
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre"),
                DB::raw("COALESCE(ad.apaterno, gr.apaterno) as apaterno"),
                DB::raw("COALESCE(ad.amaterno, gr.amaterno) as amaterno"),
                DB::raw("COALESCE(ad.telefono, gr.telefono) as telefono"),
                'ad.id_admin_destinos',
                'gr.id_gestor_rutas'
            )
            ->first();

        abort_if(!$solicitud, 404);

        return view('admin.solicitudes.show', compact('solicitud'));
    }

    public function create()
    {
        return view('admin.solicitudes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'correo'   => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8',
            'rol'      => 'required|in:admin_destinos,gestor_rutas',
            'nombre'   => 'required|string',
            'apaterno' => 'required|string',
            'telefono' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $idUsuario = DB::table('usuario')->insertGetId([
                'correo'          => $request->correo,
                'password'        => Hash::make($request->password),
                'rol'             => $request->rol,
                'estado'          => 'aprobado',
                'activo'          => 1,
                'fecha_solicitud' => now(),
                'fecha_respuesta' => now(),
            ]);

            $tabla = ($request->rol === 'admin_destinos') ? 'admin_destinos' : 'gestor_rutas';

            DB::table($tabla)->insert([
                'nombre'     => $request->nombre,
                'apaterno'   => $request->apaterno,
                'amaterno'   => $request->amaterno,
                'telefono'   => $request->telefono,
                'id_usuario' => $idUsuario,
            ]);
        });

        return redirect()->route('admin.solicitudes.index')->with('ok', 'Usuario creado exitosamente');
    }

    public function edit($id)
    {
        $usuario = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->select(
                'u.*',
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre"),
                DB::raw("COALESCE(ad.apaterno, gr.apaterno) as apaterno"),
                DB::raw("COALESCE(ad.amaterno, gr.amaterno) as amaterno"),
                DB::raw("COALESCE(ad.telefono, gr.telefono) as telefono")
            )
            ->first();

        if (!$usuario) {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Usuario no encontrado');
        }

        return view('admin.solicitudes.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'correo'   => 'required|email',
            'nombre'   => 'required|string',
            'apaterno' => 'required|string',
            'rol'      => 'required|in:admin_destinos,gestor_rutas',
            'telefono' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request, $id) {
            $datosUsuario = [
                'correo' => $request->correo,
                'rol'    => $request->rol,
            ];

            if ($request->filled('password')) {
                $datosUsuario['password'] = Hash::make($request->password);
            }

            DB::table('usuario')->where('id_usuario', $id)->update($datosUsuario);

            $tabla = ($request->rol === 'admin_destinos') ? 'admin_destinos' : 'gestor_rutas';

            DB::table($tabla)->where('id_usuario', $id)->update([
                'nombre'   => $request->nombre,
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

        $nuevoEstado = $usuario->activo ? 0 : 1;
        $mensaje = $nuevoEstado ? 'Usuario habilitado con éxito.' : 'Usuario suspendido correctamente.';

        DB::table('usuario')->where('id_usuario', $id)->update(['activo' => $nuevoEstado]);

        return back()->with('ok', $mensaje);
    }

    public function aprobar($id)
    {
        $u = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->select('u.*', DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre_persona"))
            ->first();

        abort_if(!$u, 404);

        if ($u->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        DB::table('usuario')->where('id_usuario', $id)->update([
            'estado'          => 'aprobado',
            'activo'          => 1,
            'fecha_respuesta' => now(),
            'motivo_rechazo'  => null,
        ]);

        try {
            Mail::to($u->correo)->send(new SolicitudAprobadaMail($u));
        } catch (\Exception $e) {
            // Log error pero continúa
        }

        return redirect()->route('admin.solicitudes.index')->with('ok', 'Solicitud aprobada y correo enviado.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => ['required', 'min:5'],
        ]);

        $u = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->select('u.*', DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre_persona"))
            ->first();

        abort_if(!$u, 404);

        if ($u->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        DB::table('usuario')->where('id_usuario', $id)->update([
            'estado'          => 'rechazado',
            'activo'          => 0,
            'fecha_respuesta' => now(),
            'motivo_rechazo'  => $request->motivo_rechazo,
        ]);

        try {
            Mail::to($u->correo)->send(new RechazoSolicitudMail($request->motivo_rechazo, $u->nombre_persona));
        } catch (\Exception $e) {
            // Log error pero continúa
        }

        return redirect()->route('admin.solicitudes.index')->with('ok', 'Solicitud rechazada y notificación enviada.');
    }
}