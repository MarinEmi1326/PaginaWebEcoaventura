<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use App\Mail\SolicitudPendienteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Mostrar formulario registro admin destinos
    public function showRegistroDestinos()
    {
        return view('auth.registro-destinos');
    }

    // Mostrar formulario registro gestor rutas
    public function showRegistroRutas()
    {
        return view('auth.registro-rutas');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = ['correo' => $request->correo, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $rol = $user->persona?->roles->first()?->descripcion;

            if ($rol === 'admin_general') {
                return redirect()->route('admin.index');
            }
            if ($rol === 'admin_destinos') {
                return redirect()->route('admin.index');
            }
            if ($rol === 'gestor_rutas') {
                return redirect()->route('admin.index'); // o a su panel si tiene uno
            }
            return redirect('/');
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas'])->withInput();
    }

    // Registro de admin destinos (con apaterno y amaterno)
    public function registroDestinos(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => ['required', 'string', 'max:60'],
            'apellidos' => ['required', 'string', 'max:120'],
            'telefono'  => ['required', 'digits:10'],
            'correo'    => ['required', 'email', 'unique:usuario,correo'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ]);

        try {
            $rol = Rol::where('descripcion', 'admin_destinos')->first();
            if (!$rol) {
                throw new \Exception('El rol "admin_destinos" no está configurado en la base de datos.');
            }

            DB::beginTransaction();

            $usuario = Usuario::create([
                'correo'             => $validated['correo'],
                'password'           => Hash::make($validated['password']),
                'activo'             => false,
                'estado'             => 'pendiente',
                'correo_verificado'  => 0,
                'token_verificacion' => Str::random(60),
                'fecha_solicitud'    => now(),
            ]);

            $persona = Persona::create([
                'id_usuario' => $usuario->id_usuario,
                'nombre'     => $validated['nombre'],
                'apellidos'  => $validated['apellidos'],
                'telefono'   => $validated['telefono'],
            ]);

            $persona->roles()->attach($rol->id_rol);

            DB::commit();

            try {
                Mail::to($usuario->correo)->send(new SolicitudPendienteMail($usuario));
            } catch (\Exception $e) {
                Log::warning("Error al enviar correo de solicitud pendiente: " . $e->getMessage());
            }

            return redirect()->route('registro.destinos.exito');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en registro de admin_destinos: " . $e->getMessage());
            $errorMsg = str_contains($e->getMessage(), 'rol "admin_destinos"') 
                ? $e->getMessage() 
                : 'Ocurrió un error al procesar el registro. Intenta de nuevo más tarde.';
            return back()->withInput()->with('error', $errorMsg);
        }
    }

    // Registro de gestor rutas (con apellidos completo)
    public function registroRutas(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => ['required', 'string', 'max:60'],
            'apellidos' => ['required', 'string', 'max:120'],
            'telefono'  => ['required', 'digits:10'],
            'correo'    => ['required', 'email', 'unique:usuario,correo'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ]);

        try {
            $rol = Rol::where('descripcion', 'gestor_rutas')->first();
            if (!$rol) {
                throw new \Exception('El rol "gestor_rutas" no está configurado en la base de datos.');
            }

            DB::beginTransaction();

            $usuario = Usuario::create([
                'correo'             => $validated['correo'],
                'password'           => Hash::make($validated['password']),
                'activo'             => false,
                'estado'             => 'pendiente',
                'correo_verificado'  => 0,
                'token_verificacion' => Str::random(60),
                'fecha_solicitud'    => now(),
            ]);

            $persona = Persona::create([
                'id_usuario' => $usuario->id_usuario,
                'nombre'     => $validated['nombre'],
                'apellidos'  => $validated['apellidos'],
                'telefono'   => $validated['telefono'],
            ]);

            $persona->roles()->attach($rol->id_rol);

            DB::commit();

            try {
                Mail::to($usuario->correo)->send(new SolicitudPendienteMail($usuario));
            } catch (\Exception $e) {
                Log::warning("Error al enviar correo de solicitud pendiente: " . $e->getMessage());
            }

            return redirect()->route('registro.rutas.exito');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en registro de gestor_rutas: " . $e->getMessage());

            $errorMsg = str_contains($e->getMessage(), 'rol "gestor_rutas"')
                ? $e->getMessage()
                : 'Ocurrió un error al procesar el registro. Intenta de nuevo más tarde.';

            return back()->withInput()->with('error', $errorMsg);
        }
    }

    // Verificación de correo
    public function verificarCorreo($token)
    {
        $usuario = Usuario::where('token_verificacion', $token)->first();

        if (!$usuario) {
            return redirect('/login')->with('error', 'El enlace de verificación no es válido.');
        }

        $usuario->update([
            'correo_verificado'  => 1,
            'token_verificacion' => null,
        ]);

        return redirect('/login')->with('ok', 'Correo verificado correctamente. Ahora un administrador revisará tu solicitud.');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}