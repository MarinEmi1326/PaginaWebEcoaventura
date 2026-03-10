<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withInput($request->only('correo'))->withErrors([
                'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
        }

        if ($usuario->estado === 'pendiente') {
            return back()->withInput($request->only('correo'))->withErrors([
                'correo' => 'Tu cuenta está pendiente de aprobación.',
            ]);
        }

        if ($usuario->estado === 'rechazado') {
            return back()->withInput($request->only('correo'))->withErrors([
                'correo' => 'Tu cuenta fue rechazada. Contacta al administrador.',
            ]);
        }

        if (!$usuario->activo) {
            return back()->withInput($request->only('correo'))->withErrors([
                'correo' => 'Tu cuenta ha sido suspendida o inhabilitada por el administrador.',
            ]);
        }

        Auth::login($usuario);
        $request->session()->regenerate();

        return match ($usuario->rol) {
            'admin_general'  => redirect()->route('admin.index'),
            'admin_destinos' => redirect()->route('destinos.dashboard'),
            'gestor_rutas'   => redirect()->route('rutas.dashboard'),
            'turista'        => redirect()->route('turista.dashboard'),
            default          => redirect()->route('home'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}