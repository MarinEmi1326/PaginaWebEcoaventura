<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RechazoSolicitudMail;
use App\Mail\SolicitudAprobadaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminSolicitudesController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('usuario as u')
        ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
        ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
        ->whereIn('u.rol', ['admin_destinos', 'gestor_rutas'])
        ->where('u.correo_verificado', 1);

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('u.estado', $request->estado);
        }

        $solicitudes = $query->select(
                'u.id_usuario',
                'u.correo',
                'u.rol',
                'u.activo',
                'u.estado',
                'u.fecha_solicitud',
                'u.fecha_respuesta',
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
            ->where('u.correo_verificado', 1)
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
                DB::raw("COALESCE(ad.telefono, gr.telefono) as telefono")
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
            'password' => 'required|string|min:8',
            'rol'      => 'required|in:admin_destinos,gestor_rutas',
            'nombre'   => 'required|string|max:60',
            'apaterno' => 'required|string|max:60',
            'amaterno' => 'nullable|string|max:60',
            'telefono' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $idUsuario = DB::table('usuario')->insertGetId([
                'correo'          => $request->correo,
                'password'        => Hash::make($request->password),
                'rol'             => $request->rol,
                'estado'          => 'aprobado',
                'activo'          => 1,
                'correo_verificado'   => 1,
                'token_verificacion'  => null,
                'fecha_solicitud' => now(),
                'fecha_respuesta' => now(),
            ]);

            $tablaPerfil = $request->rol === 'admin_destinos'
                ? 'admin_destinos'
                : 'gestor_rutas';

            DB::table($tablaPerfil)->insert([
                'id_usuario' => $idUsuario,
                'nombre'     => $request->nombre,
                'apaterno'   => $request->apaterno,
                'amaterno'   => $request->amaterno,
                'telefono'   => $request->telefono,
            ]);
        });

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        $usuario = DB::table('usuario as u')
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
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre"),
                DB::raw("COALESCE(ad.apaterno, gr.apaterno) as apaterno"),
                DB::raw("COALESCE(ad.amaterno, gr.amaterno) as amaterno"),
                DB::raw("COALESCE(ad.telefono, gr.telefono) as telefono")
            )
            ->first();

        if (!$usuario) {
            return redirect()
                ->route('admin.solicitudes.index')
                ->with('error', 'Usuario no encontrado.');
        }

        return view('admin.solicitudes.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuarioActual = DB::table('usuario')->where('id_usuario', $id)->first();

        if (!$usuarioActual) {
            return redirect()
                ->route('admin.solicitudes.index')
                ->with('error', 'Usuario no encontrado.');
        }

        $request->validate([
            'correo' => [
                'required',
                'email',
                Rule::unique('usuario', 'correo')->ignore($id, 'id_usuario'),
            ],
            'password' => 'nullable|string|min:8',
            'rol'      => 'required|in:admin_destinos,gestor_rutas',
            'nombre'   => 'required|string|max:60',
            'apaterno' => 'required|string|max:60',
            'amaterno' => 'nullable|string|max:60',
            'telefono' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request, $id, $usuarioActual) {
            $datosUsuario = [
                'correo' => $request->correo,
                'rol'    => $request->rol,
            ];

            if ($request->filled('password')) {
                $datosUsuario['password'] = Hash::make($request->password);
            }

            DB::table('usuario')
                ->where('id_usuario', $id)
                ->update($datosUsuario);

            $tablaNueva = $request->rol === 'admin_destinos'
                ? 'admin_destinos'
                : 'gestor_rutas';

            $tablaAnterior = $usuarioActual->rol === 'admin_destinos'
                ? 'admin_destinos'
                : 'gestor_rutas';

            $datosPerfil = [
                'nombre'   => $request->nombre,
                'apaterno' => $request->apaterno,
                'amaterno' => $request->amaterno,
                'telefono' => $request->telefono,
            ];

            if ($tablaAnterior === $tablaNueva) {
                DB::table($tablaNueva)
                    ->where('id_usuario', $id)
                    ->update($datosPerfil);
            } else {
                DB::table($tablaAnterior)
                    ->where('id_usuario', $id)
                    ->delete();

               DB::table($tablaNueva)->insert([
                    'id_usuario' => $id,
                    'nombre'     => $request->nombre,
                    'apaterno'   => $request->apaterno,
                    'amaterno'   => $request->amaterno,
                    'telefono'   => $request->telefono,
                ]);
            }
        });

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Usuario actualizado correctamente.');
    }

    public function toggleActivo($id)
    {
        $usuario = DB::table('usuario')
            ->where('id_usuario', $id)
            ->whereIn('rol', ['admin_destinos', 'gestor_rutas'])
            ->first();

        if (!$usuario) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        $nuevoEstado = $usuario->activo ? 0 : 1;

        DB::table('usuario')
            ->where('id_usuario', $id)
            ->update(['activo' => $nuevoEstado]);

        return back()->with(
            'ok',
            $nuevoEstado ? 'Usuario habilitado con éxito.' : 'Usuario suspendido correctamente.'
        );
    }

    public function aprobar($id)
    {
        $u = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->whereIn('u.rol', ['admin_destinos', 'gestor_rutas'])
            ->where('u.correo_verificado', 1)
            ->select(
                'u.*',
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre_persona")
            )
            ->first();

        abort_if(!$u, 404);

        if ($u->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        DB::table('usuario')
            ->where('id_usuario', $id)
            ->update([
                'estado'          => 'aprobado',
                'activo'          => 1,
                'fecha_respuesta' => now(),
                'motivo_rechazo'  => null,
            ]);

        try {
            Mail::to($u->correo)->send(new SolicitudAprobadaMail($u));
        } catch (\Exception $e) {
        }

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Solicitud aprobada y correo enviado.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|min:5|max:150',
        ]);

        $u = DB::table('usuario as u')
            ->leftJoin('admin_destinos as ad', 'ad.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('gestor_rutas as gr', 'gr.id_usuario', '=', 'u.id_usuario')
            ->where('u.id_usuario', $id)
            ->whereIn('u.rol', ['admin_destinos', 'gestor_rutas'])
            ->where('u.correo_verificado', 1)
            ->select(
                'u.*',
                DB::raw("COALESCE(ad.nombre, gr.nombre) as nombre_persona")
            )
            ->first();

        abort_if(!$u, 404);

        if ($u->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue atendida.');
        }

        DB::table('usuario')
            ->where('id_usuario', $id)
            ->update([
                'estado'          => 'rechazado',
                'activo'          => 0,
                'fecha_respuesta' => now(),
                'motivo_rechazo'  => $request->motivo_rechazo,
            ]);

        try {
            Mail::to($u->correo)->send(
                new RechazoSolicitudMail($request->motivo_rechazo, $u->nombre_persona)
            );
        } catch (\Exception $e) {
        }

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Solicitud rechazada y notificación enviada.');
    }
}