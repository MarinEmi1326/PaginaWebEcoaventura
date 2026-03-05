<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Turista;
use App\Models\AdminDestinos;
use App\Models\GestorRutas;
use Illuminate\Support\Facades\Hash;

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
    // YA LO TIENES — sin cambios
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
            'correo'   => $request->correo,
            'password' => bcrypt($request->password),
            'rol'      => 'turista',
            'activo'   => true,
            'estado'   => 'aprobado',
        ]);

        Turista::create([
            'nombre'     => $request->nombre,
            'apaterno'   => $request->apaterno,
            'amaterno'   => $request->amaterno,
            'id_usuario' => $usuario->id_usuario,
        ]);

        Auth::login($usuario);
        return redirect('/turista/dashboard');
    }

    public function login(Request $request)
    {
        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->with('error', 'Credenciales incorrectas.');
        }

        if ($usuario->estado === 'pendiente') {
            return back()->with('error', 'Tu cuenta está pendiente de aprobación.');
        }

        if ($usuario->estado === 'rechazado') {
            return back()->with('error', 'Tu cuenta fue rechazada. Contacta al administrador.');
        }

        if (!$usuario->activo) {
            return back()->with('error', 'Tu cuenta está desactivada.');
        }

        Auth::login($usuario);

        return match($usuario->rol) {
            'admin_general'  => redirect('/admin/index'),
            'admin_destinos' => redirect('/destinos/dashboard'),
            'gestor_rutas'   => redirect('/rutas/dashboard'),
            'turista'        => redirect('/turista/dashboard'),
        };
    }

    // ================================
    // NUEVO — Registro Admin Destinos
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

        $usuario = Usuario::create([
            'correo'           => $request->correo,
            'password'         => bcrypt($request->password),
            'rol'              => 'admin_destinos',
            'activo'           => true,
            'estado'           => 'pendiente',
            'fecha_solicitud'  => now(),
        ]);

        AdminDestinos::create([
            'nombre'      => $request->nombre,
            'apaterno'    => $request->apaterno,
            'amaterno'    => $request->amaterno,
            'telefono'    => $request->telefono,
            'id_usuario'  => $usuario->id_usuario,
        ]);

        return redirect('/login')->with('success', 'Solicitud enviada. Espera la aprobación del administrador.');
    }

    // ================================
    // NUEVO — Registro Gestor Rutas
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

        $usuario = Usuario::create([
            'correo'          => $request->correo,
            'password'        => bcrypt($request->password),
            'rol'             => 'gestor_rutas',
            'activo'          => true,
            'estado'          => 'pendiente',
            'fecha_solicitud' => now(),
        ]);

        GestorRutas::create([
            'nombre'     => $request->nombre,
            'apaterno'   => $request->apaterno,
            'amaterno'   => $request->amaterno,
            'telefono'   => $request->telefono,
            'id_usuario' => $usuario->id_usuario,
        ]);

        return redirect('/login')->with('success', 'Solicitud enviada. Espera la aprobación del administrador.');
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