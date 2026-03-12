<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Usuario;
use App\Models\Turista;
use App\Models\AdminDestinos;
use App\Models\GestorRutas;
use App\Mail\SolicitudPendienteMail;

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
        $request->validate([
            'nombre'   => 'required',
            'apaterno' => 'required',
            'correo'   => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'correo'              => $request->correo,
            'password'            => bcrypt($request->password),
            'rol'                 => 'turista',
            'activo'              => true,
            'estado'              => 'aprobado',
            'correo_verificado'   => 1,
            'token_verificacion'  => null,
            'fecha_solicitud'     => now(),
            'fecha_respuesta'     => now(),
            'motivo_rechazo'      => null,
        ]);

        Turista::create([
            'nombre'     => $request->nombre,
            'apaterno'   => $request->apaterno,
            'amaterno'   => $request->amaterno,
            'id_usuario' => $usuario->id_usuario,
        ]);

        Auth::login($usuario);

        return redirect('/');
    }

    // ================================
    // LOGIN
    // ================================

    public function login(Request $request)
    {
        $request->validate([
            'correo'   => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Credenciales incorrectas.');
        }

        if (!$usuario->correo_verificado) {
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

        if (!$usuario->activo) {
            return back()->withInput($request->only('correo'))
                ->with('error', 'Tu cuenta está desactivada.');
        }

        Auth::login($usuario);

        return match ($usuario->rol) {
            'admin_general'  => redirect('/admin/index'),
            'admin_destinos' => redirect()->route('misdestinos.index'),
            'gestor_rutas'   => redirect()->route('rutas.dashboard'),
            'turista'        => redirect('/'),
            default          => redirect('/login')->with('error', 'Rol no válido.'),
        };
    }

    // ================================
    // REGISTRO ADMIN DESTINOS
    // ================================

    public function registroDestinos(Request $request)
    {
        $request->validate([
            'nombre'    => 'required',
            'apaterno'  => 'required',
            'telefono'  => 'required|digits:10',
            'correo'    => 'required|email|unique:usuario,correo',
            'password'  => 'required|min:8|confirmed',
        ]);

        $token = Str::random(60);

        $usuario = Usuario::create([
            'correo'              => $request->correo,
            'password'            => bcrypt($request->password),
            'rol'                 => 'admin_destinos',
            'activo'              => false,
            'estado'              => 'pendiente',
            'correo_verificado'   => 0,
            'token_verificacion'  => $token,
            'fecha_solicitud'     => now(),
            'fecha_respuesta'     => null,
            'motivo_rechazo'      => null,
        ]);

        AdminDestinos::create([
            'nombre'     => $request->nombre,
            'apaterno'   => $request->apaterno,
            'amaterno'   => $request->amaterno,
            'telefono'   => $request->telefono,
            'id_usuario' => $usuario->id_usuario,
        ]);

        Mail::to($usuario->correo)->send(new SolicitudPendienteMail($usuario));

        return redirect()->route('registro.destinos.exito');
    }

    // ================================
    // REGISTRO GESTOR RUTAS
    // ================================

    public function registroRutas(Request $request)
    {
        $request->validate([
            'nombre'    => 'required',
            'apaterno'  => 'required',
            'telefono'  => 'required|digits:10',
            'correo'    => 'required|email|unique:usuario,correo',
            'password'  => 'required|min:8|confirmed',
        ]);

        $token = Str::random(60);

        $usuario = Usuario::create([
            'correo'              => $request->correo,
            'password'            => bcrypt($request->password),
            'rol'                 => 'gestor_rutas',
            'activo'              => false,
            'estado'              => 'pendiente',
            'correo_verificado'   => 0,
            'token_verificacion'  => $token,
            'fecha_solicitud'     => now(),
            'fecha_respuesta'     => null,
            'motivo_rechazo'      => null,
        ]);

        GestorRutas::create([
            'nombre'     => $request->nombre,
            'apaterno'   => $request->apaterno,
            'amaterno'   => $request->amaterno,
            'telefono'   => $request->telefono,
            'id_usuario' => $usuario->id_usuario,
        ]);

        Mail::to($usuario->correo)->send(new SolicitudPendienteMail($usuario));

        return redirect('/login')->with('success', 'Solicitud enviada. Espera la aprobación del administrador.');
    }

    // ================================
    // VERIFICAR CORREO
    // ================================

    public function verificarCorreo($token)
    {
        $usuario = Usuario::where('token_verificacion', $token)->first();

        if (!$usuario) {
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
}