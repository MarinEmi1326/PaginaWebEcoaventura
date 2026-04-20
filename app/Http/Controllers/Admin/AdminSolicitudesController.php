<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use App\Mail\SolicitudAprobadaMail;
use App\Mail\RechazoSolicitudMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminSolicitudesController extends Controller
{
    /**
     * DASHBOARD PRINCIPAL
     */
    public function dashboard()
    {
        $totalUsuarios = DB::table('usuario')->count();
        $pendientes    = DB::table('usuario')->where('estado', 'pendiente')->count();
        $publicados    = DB::table('usuario')->where('estado', 'aprobado')->count();
        $rechazados    = DB::table('usuario')->where('estado', 'rechazado')->count();

        // Cola de aprobación (usuarios pendientes)
        $colaAprobacion = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->select('u.*', 'p.nombre', 'p.apellidos', 'r.descripcion as rol')
            ->where('r.descripcion', '!=', 'admin_general')
            ->where('u.estado', 'pendiente')
            ->orderByDesc('u.fecha_solicitud')
            ->get();

        // Actividad reciente: destinos creados en la última semana
        $fechaHaceUnaSemana = Carbon::now()->subWeek();
        $actividadReciente = DB::table('destino as d')
            ->join('persona as p', 'p.id_persona', '=', 'd.creado_por')
            ->select(
                'd.id_destino',
                'd.nombre as destino_nombre',
                'd.fecha_creacion',
                'p.nombre as creador_nombre',
                'p.apellidos as creador_apellidos'
            )
            ->where('d.fecha_creacion', '>=', $fechaHaceUnaSemana)
            ->orderByDesc('d.fecha_creacion')
            ->get();

        return view('admin.index', compact(
            'totalUsuarios',
            'pendientes',
            'publicados',
            'rechazados',
            'colaAprobacion',
            'actividadReciente'
        ));
    }

    /**
     * LISTADO DE USUARIOS
     */
    public function index()
    {
        $solicitudes = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->select('u.*', 'p.nombre', 'p.apellidos', 'r.descripcion as rol')
            ->where('r.descripcion', '!=', 'admin_general')
            ->orderByDesc('u.id_usuario')
            ->get();

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    /**
     * MOSTRAR DETALLE
     */
    public function show($id)
    {
        $solicitud = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->select(
                'u.*',
                'p.nombre',
                'p.apellidos',
                'p.telefono',
                'r.descripcion as rol'
            )
            ->where('u.id_usuario', $id)
            ->first();

        if (!$solicitud) {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Usuario no encontrado');
        }

        $reportes = collect();
        $reportesRecibidos = collect();
        $reportesEnviados = collect();

        return view('admin.solicitudes.show', compact(
            'solicitud',
            'reportes',
            'reportesRecibidos',
            'reportesEnviados'
        ));
    }

    /**
     * FORMULARIO EDITAR
     */
    public function edit($id)
    {
        $usuario = DB::table('usuario as u')
            ->join('persona as p', 'p.id_usuario', '=', 'u.id_usuario')
            ->join('persona_rol as pr', 'pr.id_persona', '=', 'p.id_persona')
            ->join('rol as r', 'r.id_rol', '=', 'pr.id_rol')
            ->select(
                'u.id_usuario',
                'u.correo',
                'u.activo',
                'u.estado',
                'p.nombre',
                'p.apellidos',
                'p.telefono',
                'r.descripcion as rol'
            )
            ->where('u.id_usuario', $id)
            ->first();

        if (!$usuario) {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Usuario no encontrado');
        }

        $apellidos = $usuario->apellidos ?? '';
        $espacio = strpos($apellidos, ' ');
        if ($espacio !== false) {
            $usuario->apaterno = substr($apellidos, 0, $espacio);
            $usuario->amaterno = substr($apellidos, $espacio + 1);
        } else {
            $usuario->apaterno = $apellidos;
            $usuario->amaterno = '';
        }

        return view('admin.solicitudes.edit', compact('usuario'));
    }

    /**
     * ACTUALIZAR USUARIO
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'correo'   => 'required|email|unique:usuario,correo,' . $id . ',id_usuario',
            'password' => 'nullable|min:8|confirmed',
            'rol'      => 'required|in:admin_destinos,gestor_rutas,turista',
            'nombre'   => 'required|string|max:60',
            'apaterno' => 'required|string|max:60',
            'amaterno' => 'nullable|string|max:60',
            'telefono' => 'required|digits:10',
        ]);

        try {
            DB::beginTransaction();

            $usuarioData = ['correo' => $validated['correo']];
            if (!empty($validated['password'])) {
                $usuarioData['password'] = Hash::make($validated['password']);
            }
            Usuario::where('id_usuario', $id)->update($usuarioData);

            $persona = Persona::where('id_usuario', $id)->first();
            if (!$persona) {
                throw new \Exception('Persona no encontrada para este usuario');
            }

            $apellidosCompleto = trim($validated['apaterno'] . ' ' . ($validated['amaterno'] ?? ''));
            $persona->update([
                'nombre'    => $validated['nombre'],
                'apellidos' => $apellidosCompleto,
                'telefono'  => $validated['telefono'],
            ]);

            $nuevoRol = Rol::where('descripcion', $validated['rol'])->first();
            if (!$nuevoRol) {
                throw new \Exception('Rol no válido');
            }
            $persona->roles()->sync([$nuevoRol->id_rol]);

            DB::commit();
            return redirect()->route('admin.solicitudes.index')->with('ok', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar usuario: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * FORMULARIO CREAR (admin)
     */
    public function create()
    {
        return view('admin.solicitudes.create');
    }

    /**
     * GUARDAR NUEVO USUARIO (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'correo'   => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8|confirmed',
            'rol'      => 'required|in:admin_destinos,gestor_rutas',
            'nombre'   => 'required|string|max:60',
            'apellidos'=> 'required|string|max:120',
            'telefono' => 'required|digits:10',
        ]);

        try {
            DB::beginTransaction();

            $rol = Rol::where('descripcion', $validated['rol'])->first();
            if (!$rol) {
                throw new \Exception('El rol seleccionado no existe en la BD.');
            }

            $usuario = Usuario::create([
                'correo'             => $validated['correo'],
                'password'           => Hash::make($validated['password']),
                'activo'             => true,
                'estado'             => 'aprobado',
                'correo_verificado'  => 1,
                'token_verificacion' => null,
                'fecha_solicitud'    => now(),
                'fecha_respuesta'    => now(),
            ]);

            $persona = Persona::create([
                'id_usuario' => $usuario->id_usuario,
                'nombre'     => $validated['nombre'],
                'apellidos'  => $validated['apellidos'],
                'telefono'   => $validated['telefono'],
            ]);

            $persona->roles()->attach($rol->id_rol);

            DB::commit();
            return redirect()->route('admin.solicitudes.index')->with('ok', 'Usuario creado y aprobado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear usuario desde admin: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear: ' . $e->getMessage());
        }
    }

    /**
     * APROBAR SOLICITUD
     */
    public function aprobar($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) return back()->with('error', 'Usuario no encontrado');
        if ($usuario->estado !== 'pendiente') return back()->with('error', 'Esta solicitud ya fue atendida.');
        $persona = $usuario->persona;
        if (!$persona) return back()->with('error', 'Datos de persona no encontrados');

        $nombreCompleto = $persona->nombre . ' ' . $persona->apellidos;

        $usuario->update([
            'estado'          => 'aprobado',
            'activo'          => true,
            'fecha_respuesta' => now(),
        ]);

        try {
            Mail::to($usuario->correo)->send(new SolicitudAprobadaMail($usuario, $nombreCompleto));
        } catch (\Exception $e) {
            Log::warning("Error al enviar correo de aprobación: " . $e->getMessage());
        }

        return back()->with('ok', 'Usuario aprobado correctamente.');
    }

    /**
     * RECHAZAR SOLICITUD
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate(['motivo_rechazo' => 'required|string|max:255']);

        $usuario = Usuario::find($id);
        if (!$usuario) return back()->with('error', 'Usuario no encontrado');
        if ($usuario->estado !== 'pendiente') return back()->with('error', 'Esta solicitud ya fue atendida.');

        $persona = $usuario->persona;
        $nombreCompleto = $persona ? ($persona->nombre . ' ' . $persona->apellidos) : 'Usuario';

        $usuario->update([
            'estado'          => 'rechazado',
            'activo'          => false,
            'motivo_rechazo'  => $request->motivo_rechazo,
            'fecha_respuesta' => now(),
        ]);

        try {
            Mail::to($usuario->correo)->send(new RechazoSolicitudMail($request->motivo_rechazo, $nombreCompleto));
        } catch (\Exception $e) {
            Log::warning("Error al enviar correo de rechazo: " . $e->getMessage());
        }

        return back()->with('ok', 'Solicitud rechazada.');
    }

    /**
     * SUSPENDER / HABILITAR
     */
    public function toggle($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) return back()->with('error', 'Usuario no encontrado');

        if ($usuario->estado === 'pendiente') {
            return back()->with('error', 'No se puede cambiar el estado de una solicitud pendiente. Aprueba o rechaza primero.');
        }

        $nuevoEstado = !$usuario->activo;
        $usuario->update(['activo' => $nuevoEstado]);

        $mensaje = $nuevoEstado ? 'Usuario habilitado correctamente.' : 'Usuario suspendido correctamente.';
        return back()->with('ok', $mensaje);
    }
}