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
    public function showLogin() 
    { 
        return view('auth.login'); 
    }
    
    // ========== REGISTRO ADMIN DESTINOS ==========
    public function showRegistroDestinos() 
    { 
        return view('auth.registro-destinos'); 
    }

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
            if (!$rol) throw new \Exception('Rol admin_destinos no encontrado.');

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
            } catch (\Exception $e) { Log::warning("Mail error: " . $e->getMessage()); }

            return redirect()->route('registro.destinos.exito');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ========== REGISTRO GESTOR RUTAS ==========
    public function showRegistroRutas()
    {
        return view('auth.registro-rutas');
    }

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
            if (!$rol) throw new \Exception('Rol gestor_rutas no encontrado.');

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
            } catch (\Exception $e) { Log::warning("Mail error: " . $e->getMessage()); }

            return redirect()->route('registro.rutas.exito');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ========== REGISTRO TURISTA ==========
    public function showRegistroTurista()
    {
        return view('auth.registro-turista');
    }

    public function registroTurista(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => ['required', 'string', 'max:60'],
            'apellidos' => ['required', 'string', 'max:120'],
            'telefono'  => ['required', 'digits:10'],
            'correo'    => ['required', 'email', 'unique:usuario,correo'],
            'password'  => ['required', 'min:8', 'confirmed'],
        ]);

        try {
            $rol = Rol::where('descripcion', 'turista')->first();
            if (!$rol) throw new \Exception('Rol turista no encontrado.');

            DB::beginTransaction();
            $usuario = Usuario::create([
                'correo'             => $validated['correo'],
                'password'           => Hash::make($validated['password']),
                'activo'             => true,           // Turista activo automáticamente
                'estado'             => 'aprobado',     // Aprobado sin revisión
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

            // Opcional: enviar correo de bienvenida
            // Mail::to($usuario->correo)->send(new BienvenidaTuristaMail($usuario));

            return redirect()->route('login')->with('ok', 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    // ========== LOGIN Y OTROS MÉTODOS ==========
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario por correo
        $user = Usuario::where('correo', $request->correo)->first();

        // CORREO NO EXISTE
        if (!$user) {
            return back()->with('error_tipo', 'correo')->withInput();
        }

        // CONTRASEÑA INCORRECTA
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error_tipo', 'password')->withInput();
        }

        // LOGIN CORRECTO
        Auth::login($user);

        $rol = $user->persona?->roles->first()?->descripcion;

        // ROLES 
        if ($rol === 'admin_general') return redirect()->route('admin.index');
        if ($rol === 'admin_destinos') return redirect()->route('misdestinos.index');
        if ($rol === 'gestor_rutas') return redirect()->route('rutas.index');

        return redirect('/');

    }

    public function verificarCorreo($token)
    {
        $usuario = Usuario::where('token_verificacion', $token)->first();
        if (!$usuario) return redirect('/login')->with('error', 'Enlace inválido.');
        $usuario->update(['correo_verificado' => 1, 'token_verificacion' => null]);
        return redirect('/login')->with('ok', 'Correo verificado. Ahora un administrador revisará tu solicitud.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}