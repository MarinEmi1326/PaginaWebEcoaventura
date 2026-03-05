<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validar campos
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Intentar autenticar con la condición de que esté activo
        $credentials = [
            'correo' => $request->correo,
            'password' => $request->password,
            'activo' => 1 // <--- Solo usuarios habilitados
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirección según rol
            $rol = Auth::user()->rol;
            if ($rol === 'admin') return redirect()->route('admin.index');
            if ($rol === 'hotelero') return redirect()->route('hotelero.index');
            if ($rol === 'restaurantero') return redirect()->route('restaurantero.dashboard');
            
            return redirect()->route('home');
        }

        // 3. Si falla, verificar si fue por estar suspendido
        $usuarioExistente = Usuario::where('correo', $request->correo)->first();
        
        if ($usuarioExistente && !$usuarioExistente->activo) {
            return back()->withErrors([
                'correo' => 'Tu cuenta ha sido suspendida o inhabilitada por el administrador.',
            ]);
        }

        // Falla genérica (contraseña mal o correo inexistente)
        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}