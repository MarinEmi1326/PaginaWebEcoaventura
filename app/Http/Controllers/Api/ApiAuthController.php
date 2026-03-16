<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turista;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    // POST /api/login
    public function login(Request $request)
    {
        $request->validate([
            'correo'   => 'required|email',
            'password' => 'required',
            'fcm_token' => 'nullable|string',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['success' => false, 'message' => 'Credenciales incorrectas.'], 401);
        }

        if (!$usuario->correo_verificado) {
            return response()->json(['success' => false, 'message' => 'Debes verificar tu correo.'], 403);
        }

        if ($usuario->estado === 'pendiente') {
            return response()->json(['success' => false, 'message' => 'Tu cuenta está pendiente de aprobación.'], 403);
        }

        if ($usuario->estado === 'rechazado') {
            return response()->json(['success' => false, 'message' => 'Tu cuenta fue rechazada.'], 403);
        }

        if (!$usuario->activo) {
            return response()->json(['success' => false, 'message' => 'Usuario inactivo.'], 403);
        }

        // Solo turistas y admin_destinos pueden usar la app móvil
        if (!in_array($usuario->rol, ['turista', 'admin_destinos'])) {
            return response()->json(['success' => false, 'message' => 'Rol no permitido en la app.'], 403);
        }

        // Obtener nombre según rol
        $perfil = null;
        if ($usuario->rol === 'turista') {
            $perfil = DB::table('turista')->where('id_usuario', $usuario->id_usuario)->first();
        } elseif ($usuario->rol === 'admin_destinos') {
            $perfil = DB::table('admin_destinos')->where('id_usuario', $usuario->id_usuario)->first();
        }

        $usuario->tokens()->delete();
        $token = $usuario->createToken('api-token')->plainTextToken;

        if ($request->filled('fcm_token')) {
            DB::table('usuario')
                ->where('id_usuario', $usuario->id_usuario)
                ->update(['fcm_token' => $request->fcm_token]);
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'rol'     => $usuario->rol,
            'correo'  => $usuario->correo,
            'nombre'  => $perfil->nombre ?? null,
            'apaterno' => $perfil->apaterno ?? null,
        ]);
    }

    // POST /api/registro/turista
    public function registroTurista(Request $request)
    {
        $request->validate([
            'nombre'   => 'required',
            'apaterno' => 'required',
            'correo'   => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8',
        ]);

        $usuario = Usuario::create([
            'correo'             => $request->correo,
            'password'           => bcrypt($request->password),
            'rol'                => 'turista',
            'activo'             => true,
            'estado'             => 'aprobado',
            'correo_verificado'  => 1,
            'token_verificacion' => null,
            'fecha_solicitud'    => now(),
            'fecha_respuesta'    => now(),
            'motivo_rechazo'     => null,
        ]);

        Turista::create([
            'nombre'     => $request->nombre,
            'apaterno'   => $request->apaterno,
            'amaterno'   => $request->amaterno ?? null,
            'id_usuario' => $usuario->id_usuario,
        ]);

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json([
            'success'  => true,
            'message'  => 'Registro exitoso.',
            'token'    => $token,
            'rol'      => $usuario->rol,
            'nombre'   => $request->nombre,
            'apaterno' => $request->apaterno,
        ], 201);
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['success' => true, 'message' => 'Sesión cerrada.']);
    }

    // GET /api/perfil
    public function perfil(Request $request)
    {
        $usuario = $request->user();

        $perfil = null;
        if ($usuario->rol === 'turista') {
            $perfil = DB::table('turista')->where('id_usuario', $usuario->id_usuario)->first();
        } elseif ($usuario->rol === 'admin_destinos') {
            $perfil = DB::table('admin_destinos')->where('id_usuario', $usuario->id_usuario)->first();
        }

        return response()->json([
            'success'  => true,
            'correo'   => $usuario->correo,
            'rol'      => $usuario->rol,
            'nombre'   => $perfil->nombre ?? null,
            'apaterno' => $perfil->apaterno ?? null,
            'amaterno' => $perfil->amaterno ?? null,
            'telefono' => $perfil->telefono ?? null,
        ]);
    }

    // PUT /api/perfil
    public function actualizarPerfil(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nombre'            => 'required|string|max:60',
            'apaterno'          => 'required|string|max:60',
            'amaterno'          => 'nullable|string|max:60',
            'telefono'          => 'nullable|string|max:20',
            'password_actual'   => 'nullable|string',
            'password_nuevo'    => 'nullable|string|min:8',
        ]);

        $tabla = match ($user->rol) {
            'turista'        => 'turista',
            'admin_destinos' => 'admin_destinos',
            default          => null,
        };

        if (!$tabla) {
            return response()->json(['success' => false, 'message' => 'Rol no permitido'], 403);
        }

        // Actualizar datos personales
        DB::table($tabla)->where('id_usuario', $user->id_usuario)->update([
            'nombre'   => $request->nombre,
            'apaterno' => $request->apaterno,
            'amaterno' => $request->amaterno,
            'telefono' => $request->telefono,
        ]);

        // Cambiar contraseña si se proporcionó
        if ($request->filled('password_actual') && $request->filled('password_nuevo')) {
            if (!Hash::check($request->password_actual, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 422);
            }
            DB::table('usuario')->where('id_usuario', $user->id_usuario)->update([
                'password' => bcrypt($request->password_nuevo),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Perfil actualizado correctamente']);
    }


    // GET /api/turista/pagos
    public function misPagos(Request $request)
    {
        $user = $request->user();

        if ($user->rol !== 'turista') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $turista = DB::table('turista')->where('id_usuario', $user->id_usuario)->first();

        if (!$turista) {
            return response()->json(['success' => false, 'message' => 'Turista no encontrado'], 404);
        }

        $pagos = DB::table('pago')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('pago.id_turista', $turista->id_turista)
            ->select(
                'pago.id_pago',
                'pago.monto',
                'pago.estado',
                'pago.fecha',
                'pago.moneda',
                'destino.nombre as destino_nombre',
                'paquete.nombre as paquete_nombre'
            )
            ->orderByDesc('pago.fecha')
            ->get();

        return response()->json([
            'success' => true,
            'total'   => count($pagos),
            'data'    => $pagos,
        ]);
    }
}
