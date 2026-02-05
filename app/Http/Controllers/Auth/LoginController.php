<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra la vista de login
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión
     */
    public function login(Request $request)
    {
        // Validación básica
        $request->validate([
            'correo'   => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Credenciales (solo usuarios activos)
        $credentials = [
            'correo'  => $request->correo,
            'password'=> $request->password,
            'activo'  => 1,
        ];

        $remember = $request->boolean('remember');

        // Intento de autenticación
        if (Auth::attempt($credentials, $remember)) {

            // Regenerar sesión por seguridad
            $request->session()->regenerate();

            // Usuario autenticado
            $user = Auth::user();

            // 👉 Redirección según rol
            if ($user->rol === 'admin') {
                return redirect()->route('admin.dashboard');
            }
             // ✅ Bloquear si no está aprobado
            if (in_array($user->rol, ['hotelero','restaurantero']) && $user->estado !== 'aprobado') {
                Auth::logout();
                return back()->withErrors([
                    'correo' => 'Tu cuenta aún no está aprobada por el administrador.',
                ])->onlyInput('correo');
            }
            // Otros roles (por ahora al home)
            return redirect()->route('home');
        }
       


        // Error de credenciales
        return back()->withErrors([
            'correo' => 'Credenciales inválidas o usuario inactivo.',
        ])->onlyInput('correo');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
