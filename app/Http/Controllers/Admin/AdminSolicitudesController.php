<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RechazoSolicitudMail;
use App\Mail\SolicitudAprobadaMail;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminSolicitudesController extends Controller
{
    // ================================
    // LISTAR SOLICITUDES
    // ================================
    public function index(Request $request)
    {
        $query = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas', 'turista'])
            ->where('u.correo_verificado', 1);

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('u.estado', $request->estado);
        }

        $solicitudes = $query->select(
            'u.id_usuario',
            'u.correo',
            'u.activo',
            'u.estado',
            'u.fecha_solicitud',
            'u.fecha_respuesta',
            'p.id_persona',
            'p.nombre',
            'p.apellidos',
            'p.telefono',
            'r.descripcion as rol'
        )
        ->orderByDesc('u.fecha_solicitud')
        ->get();

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    // ================================
    // VER DETALLE DE SOLICITUD
    // ================================
    public function show($id)
    {
        $solicitud = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('u.id_usuario', $id)
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas', 'turista'])
            ->where('u.correo_verificado', 1)
            ->select(
                'u.id_usuario',
                'u.correo',
                'u.activo',
                'u.estado',
                'u.fecha_solicitud',
                'u.fecha_respuesta',
                'u.motivo_rechazo',
                'p.id_persona',
                'p.nombre',
                'p.apellidos',
                'p.telefono',
                'p.facebook_url',
                'p.instagram_url',
                'p.tiktok_url',
                'r.descripcion as rol'
            )
            ->first();

        abort_if(!$solicitud, 404);

        $reportes = DB::table('reporte')
            ->leftJoin('destino', 'reporte.id_destino', '=', 'destino.id_destino')
            ->leftJoin('comentario', 'reporte.id_comentario', '=', 'comentario.id_comentario')
            ->where('reporte.reportado_por', $solicitud->id_persona)
            ->select(
                'reporte.id_reporte',
                'reporte.tipo_objeto',
                'reporte.motivo',
                'reporte.estado',
                'reporte.fecha',
                'destino.nombre as nombre_destino',
                'comentario.comentario as texto_comentario'
            )
            ->orderByDesc('reporte.fecha')
            ->get();

        $reportesRecibidos = DB::table('reporte')
            ->join('comentario', 'reporte.id_comentario', '=', 'comentario.id_comentario')
            ->join('persona as pAutor', 'comentario.id_persona', '=', 'pAutor.id_persona')
            ->leftJoin('destino', 'reporte.id_destino', '=', 'destino.id_destino')
            ->leftJoin('persona as reporter', 'reporte.reportado_por', '=', 'reporter.id_persona')
            ->where('pAutor.id_usuario', $id)
            ->select(
                'reporte.id_reporte',
                'reporte.motivo',
                'reporte.estado',
                'reporte.fecha',
                'comentario.comentario as texto_comentario',
                'destino.nombre as nombre_destino',
                'reporter.nombre as nombre_reporter',
                'reporter.apellidos as apellidos_reporter'
            )
            ->orderByDesc('reporte.fecha')
            ->get();

        return view('admin.solicitudes.show', compact('solicitud', 'reportes', 'reportesRecibidos'));
    }

    // ================================
    // CREAR NUEVO ADMIN/GESTOR (desde admin)
    // ================================
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
            'apellidos'=> 'required|string|max:120',
            'telefono' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario
            $idUsuario = DB::table('usuario')->insertGetId([
                'correo'              => $request->correo,
                'password'            => Hash::make($request->password),
                'estado'              => 'aprobado',
                'activo'              => 1,
                'correo_verificado'   => 1,
                'token_verificacion'  => null,
                'fecha_solicitud'     => now(),
                'fecha_respuesta'     => now(),
            ]);

            // Crear persona
            $idPersona = DB::table('persona')->insertGetId([
                'id_usuario' => $idUsuario,
                'nombre'     => $request->nombre,
                'apellidos'  => $request->apellidos,
                'telefono'   => $request->telefono,
            ]);

            // Obtener id del rol
            $rol = DB::table('rol')->where('descripcion', $request->rol)->first();
            
            // Asignar rol
            DB::table('persona_rol')->insert([
                'id_persona' => $idPersona,
                'id_rol'     => $rol->id_rol,
            ]);
        });

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Usuario creado exitosamente.');
    }

    // ================================
    // EDITAR USUARIO
    // ================================
    public function edit($id)
    {
        $usuario = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('u.id_usuario', $id)
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas', 'turista'])
            ->select(
                'u.id_usuario',
                'u.correo',
                'u.activo',
                'u.estado',
                'p.id_persona',
                'p.nombre',
                'p.apellidos',
                'p.telefono',
                'r.descripcion as rol'
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
            'correo'   => [
                'required',
                'email',
                Rule::unique('usuario', 'correo')->ignore($id, 'id_usuario'),
            ],
            'password' => 'nullable|string|min:8',
            'rol'      => 'required|in:admin_destinos,gestor_rutas,turista',
            'nombre'   => 'required|string|max:60',
            'apellidos'=> 'required|string|max:120',
            'telefono' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request, $id) {
            // Actualizar usuario
            $datosUsuario = ['correo' => $request->correo];
            if ($request->filled('password')) {
                $datosUsuario['password'] = Hash::make($request->password);
            }
            DB::table('usuario')->where('id_usuario', $id)->update($datosUsuario);

            // Obtener persona
            $persona = DB::table('persona')->where('id_usuario', $id)->first();
            
            // Actualizar persona
            DB::table('persona')->where('id_persona', $persona->id_persona)->update([
                'nombre'    => $request->nombre,
                'apellidos' => $request->apellidos,
                'telefono'  => $request->telefono,
            ]);

            // Obtener nuevo rol
            $nuevoRol = DB::table('rol')->where('descripcion', $request->rol)->first();
            
            // Eliminar rol actual y asignar nuevo
            DB::table('persona_rol')->where('id_persona', $persona->id_persona)->delete();
            DB::table('persona_rol')->insert([
                'id_persona' => $persona->id_persona,
                'id_rol'     => $nuevoRol->id_rol,
            ]);
        });

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Usuario actualizado correctamente.');
    }

    // ================================
    // ACTIVAR/SUSPENDER USUARIO
    // ================================
    public function toggleActivo($id)
    {
        $usuario = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('u.id_usuario', $id)
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
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

    // ================================
    // APROBAR SOLICITUD
    // ================================
    public function aprobar($id)
    {
        $u = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('u.id_usuario', $id)
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
            ->where('u.correo_verificado', 1)
            ->select('u.*', 'p.nombre as nombre_persona')
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
            // Log error si quieres
        }

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', 'Solicitud aprobada y correo enviado.');
    }

    // ================================
    // RECHAZAR SOLICITUD
    // ================================
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|min:5|max:150',
        ]);

        $u = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->where('u.id_usuario', $id)
            ->whereIn('r.descripcion', ['admin_destinos', 'gestor_rutas'])
            ->where('u.correo_verificado', 1)
            ->select('u.*', 'p.nombre as nombre_persona')
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