<?php

namespace App\Http\Controllers;

use App\Mail\SolicitudPendienteMail;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ================================
    // VISTAS
    // ================================

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegistroTurista()
    {
        return view('auth.registro-turista');
    }

    public function showRegistroDestinos()
    {
        return view('auth.registro-destinos');
    }

    public function showRegistroRutas()
    {
        return view('auth.registro-rutas');
    }

    // ================================
    // REGISTRO TURISTA
    // ================================

    public function registroTurista(Request $request)
    {
        $mensajes = $this->getMensajesRegistro();

        $validated = $request->validate([
            'nombre'    => ['required', 'regex:/^[\pL\s]+$/u'],
            'apaterno'  => ['required', 'regex:/^[\pL\s]+$/u'],
            'amaterno'  => ['nullable', 'regex:/^[\pL\s]+$/u'],
            'telefono'  => ['required', 'digits:10'],
            'correo'    => ['required', 'email', 'unique:usuario,correo'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ], $mensajes);
         
        $usuario = Usuario::create([
            'correo'             => $validated['correo'],
            'password'           => Hash::make($validated['password']),
            'activo'             => true,
            'estado'             => 'aprobado',
            'correo_verificado'  => 1,
            'token_verificacion' => null,
            'fecha_solicitud'    => now(),
            'fecha_respuesta'    => now(),
            'motivo_rechazo'     => null,
        ]);

        // Crear persona con nombre y apellidos combinados
        $nombreCompleto = $validated['nombre'];
        $apellidosCompletos = $validated['apaterno'];
        if (!empty($validated['amaterno'])) {
            $apellidosCompletos .= ' ' . $validated['amaterno'];
        }

        $persona = Persona::create([
            'nombre'     => $nombreCompleto,
            'apellidos'  => $apellidosCompletos,
            'telefono'   => $validated['telefono'],
            'id_usuario' => $usuario->id_usuario,
        ]);

        // Asignar rol TURISTA
        $rolTurista = Rol::where('descripcion', 'turista')->first();
        $persona->roles()->attach($rolTurista->id_rol);

        Auth::login($usuario);

        return redirect('/');
    }

    // ================================
    // LOGIN
    // ================================

    public function login(Request $request)
    {
        $mensajes = [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email'    => 'El correo no tiene un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];

        $request->validate([
            'correo'   => ['required', 'email'],
            'password' => ['required'],
        ], $mensajes);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (! $usuario || ! Hash::check($request->password, $usuario->password)) {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Credenciales incorrectas.');
        }

        if (! $usuario->correo_verificado) {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Debes confirmar tu correo electrónico antes de iniciar sesión.');
        }

        if ($usuario->estado === 'pendiente') {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Tu cuenta está pendiente de aprobación.');
        }

        if ($usuario->estado === 'rechazado') {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Tu cuenta fue rechazada. Contacta al administrador.');
        }

        if (! $usuario->activo) {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Tu cuenta está desactivada.');
        }

        Auth::login($usuario);

        // Obtener roles desde persona_rol
        $persona = $usuario->persona;
        $roles = $persona?->roles->pluck('descripcion')->toArray() ?? [];

        // Redirigir según el rol
        if (in_array('admin_general', $roles)) {
            return redirect()->route('admin.index');
        }
        if (in_array('admin_destinos', $roles)) {
            return redirect()->route('misdestinos.index');
        }
        if (in_array('gestor_rutas', $roles)) {
            return redirect()->route('rutas.index');
        }
        if (in_array('turista', $roles)) {
            return redirect('/');
        }

        return redirect('/login')->with('error', 'Rol no válido.');
    }

    // ================================
    // REGISTRO ADMIN DESTINOS
    // ================================

    public function registroDestinos(Request $request)
    {
        $mensajes = $this->getMensajesRegistro();

        $validated = $request->validate([
            'nombre'    => ['required', 'regex:/^[\pL\s]+$/u'],
            'apaterno'  => ['required', 'regex:/^[\pL\s]+$/u'],
            'amaterno'  => ['nullable', 'regex:/^[\pL\s]+$/u'],
            'telefono'  => ['required', 'digits:10'],
            'correo'    => ['required', 'email', 'unique:usuario,correo'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ], $mensajes);

        $token = Str::random(60);

        $usuario = Usuario::create([
            'correo'             => $validated['correo'],
            'password'           => Hash::make($validated['password']),
            'activo'             => false,
            'estado'             => 'pendiente',
            'correo_verificado'  => 0,
            'token_verificacion' => $token,
            'fecha_solicitud'    => now(),
            'fecha_respuesta'    => null,
            'motivo_rechazo'     => null,
        ]);

        $nombreCompleto = $validated['nombre'];
        $apellidosCompletos = $validated['apaterno'];
        if (!empty($validated['amaterno'])) {
            $apellidosCompletos .= ' ' . $validated['amaterno'];
        }

        $persona = Persona::create([
            'nombre'     => $nombreCompleto,
            'apellidos'  => $apellidosCompletos,
            'telefono'   => $validated['telefono'],
            'id_usuario' => $usuario->id_usuario,
        ]);

        // Asignar rol ADMIN DESTINOS
        $rolAdminDestinos = Rol::where('descripcion', 'admin_destinos')->first();
        $persona->roles()->attach($rolAdminDestinos->id_rol);

        Mail::to($usuario->correo)->send(new SolicitudPendienteMail($usuario));

        return redirect()->route('registro.destinos.exito');
    }

    // ================================
    // REGISTRO GESTOR RUTAS
    // ================================

    public function registroRutas(Request $request)
    {
        $mensajes = $this->getMensajesRegistro();

        $validated = $request->validate([
            'nombre'    => ['required', 'regex:/^[\pL\s]+$/u'],
            'apaterno'  => ['required', 'regex:/^[\pL\s]+$/u'],
            'amaterno'  => ['nullable', 'regex:/^[\pL\s]+$/u'],
            'telefono'  => ['required', 'digits:10'],
            'correo'    => ['required', 'email', 'unique:usuario,correo'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ], $mensajes);

        $token = Str::random(60);

        $usuario = Usuario::create([
            'correo'             => $validated['correo'],
            'password'           => Hash::make($validated['password']),
            'activo'             => false,
            'estado'             => 'pendiente',
            'correo_verificado'  => 0,
            'token_verificacion' => $token,
            'fecha_solicitud'    => now(),
            'fecha_respuesta'    => null,
            'motivo_rechazo'     => null,
        ]);

        $nombreCompleto = $validated['nombre'];
        $apellidosCompletos = $validated['apaterno'];
        if (!empty($validated['amaterno'])) {
            $apellidosCompletos .= ' ' . $validated['amaterno'];
        }

        $persona = Persona::create([
            'nombre'     => $nombreCompleto,
            'apellidos'  => $apellidosCompletos,
            'telefono'   => $validated['telefono'],
            'id_usuario' => $usuario->id_usuario,
        ]);

        // Asignar rol GESTOR RUTAS
        $rolGestor = Rol::where('descripcion', 'gestor_rutas')->first();
        $persona->roles()->attach($rolGestor->id_rol);

        Mail::to($usuario->correo)->send(new SolicitudPendienteMail($usuario));

        return redirect('/login')->with('success', 'Solicitud enviada. Espera la aprobación del administrador.');
    }

    // ================================
    // VERIFICAR CORREO
    // ================================

    public function verificarCorreo($token)
    {
        $usuario = Usuario::where('token_verificacion', $token)->first();

        if (! $usuario) {
            return redirect('/login')->with('error', 'Token inválido o expirado.');
        }

        $usuario->correo_verificado = 1;
        $usuario->token_verificacion = null;
        $usuario->save();

        return view('auth.correo_verificado');
    }

    // ================================
    // LOGOUT
    // ================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ================================
    // MENSAJES DE VALIDACIÓN
    // ================================

    private function getMensajesRegistro(): array
    {
        return [
            'nombre.required'    => 'El nombre es obligatorio.',
            'nombre.regex'       => 'El nombre solo debe contener letras.',
            'apaterno.required'  => 'El apellido paterno es obligatorio.',
            'apaterno.regex'     => 'El apellido paterno solo debe contener letras.',
            'amaterno.regex'     => 'El apellido materno solo debe contener letras.',
            'telefono.required'  => 'El teléfono es obligatorio.',
            'telefono.digits'    => 'El teléfono debe tener exactamente 10 dígitos (ej: 9611234567).',
            'correo.required'    => 'El correo electrónico es obligatorio.',
            'correo.email'       => 'Debes ingresar un correo válido.',
            'correo.unique'      => 'Este correo ya está en uso. ¿Ya tienes cuenta?',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'Usa al menos 8 caracteres para mayor seguridad.',
            'password.confirmed' => 'Las contraseñas no coinciden. Asegúrate de escribirlas igual.',
        ];
    }
}