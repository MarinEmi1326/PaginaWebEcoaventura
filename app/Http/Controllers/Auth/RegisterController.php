<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Turista;
use App\Models\Hotelero;
use App\Models\Restaurantero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Maneja el registro de nuevos usuarios.
     */
    public function register(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'rol' => ['required', Rule::in(['turista', 'hotelero', 'restaurantero'])],
            'nombre' => ['required', 'string', 'min:3', 'max:80'],
            'apaterno' => ['required', 'string', 'min:2', 'max:80'],
            'amaterno' => ['nullable', 'string', 'max:80'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['required', 'email', 'max:150', 'unique:usuario,correo'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        // ✅ (1) Esto evita que VS Code marque error de variable no definida
        $user = null;

        // Transacción para asegurar que los datos se registren correctamente
        DB::transaction(function () use ($request, &$user) {

            // 1) Crear en la tabla `usuario`
            $user = Usuario::create([
                'correo' => $request->correo,
                'password' => bcrypt($request->password), // Cifrado de la contraseña
                'rol' => $request->rol,
                'activo' => 1,
            ]);

            // 2) Crear el perfil correspondiente según el rol
            if ($request->rol === 'turista') {
                Turista::create([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'id_usuario' => $user->id_usuario,
                ]);
            }

            if ($request->rol === 'hotelero') {
                Hotelero::create([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'telefono' => $request->telefono,
                    'id_usuario' => $user->id_usuario,
                ]);
            }

            if ($request->rol === 'restaurantero') {
                Restaurantero::create([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'telefono' => $request->telefono,
                    'id_usuario' => $user->id_usuario,
                ]);
            }
        });

            // después del DB::transaction(...)
        if (!$user) {
            return back()->withErrors(['register' => 'No se pudo crear el usuario.']);
        }

        Auth::login($user);

        if ($user->rol === 'hotelero') {
            return redirect()->route('hotelero.dashboard')->with('success', 'Cuenta creada correctamente. ¡Bienvenido!');
        }

        if ($user->rol === 'restaurantero') {
            return redirect()->route('restaurantero.dashboard')->with('success', 'Cuenta creada correctamente. ¡Bienvenido!');
        }

        return redirect()->route('home')->with('success', 'Cuenta creada correctamente. ¡Bienvenido!');

    }
}
