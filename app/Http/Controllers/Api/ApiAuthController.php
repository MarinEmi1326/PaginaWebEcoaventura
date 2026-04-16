<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        $usuario = Usuario::with('persona.roles')->where('correo', $request->correo)->first();

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

        $persona = $usuario->persona;

        if (!$persona) {
            return response()->json(['success' => false, 'message' => 'Perfil no encontrado'], 404);
        }

        $roles = $persona->roles->pluck('descripcion');

        // Solo permitir app móvil
        if (!$roles->intersect(['turista', 'admin_destinos'])->isNotEmpty()) {
            return response()->json(['success' => false, 'message' => 'Rol no permitido en la app.'], 403);
        }

        $usuario->tokens()->delete();
        $token = $usuario->createToken('api-token')->plainTextToken;

        if ($request->filled('fcm_token')) {
            $usuario->update(['fcm_token' => $request->fcm_token]);
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'usuario' => [
                'id_usuario' => $usuario->id_usuario,
                'correo' => $usuario->correo,
            ],
            'persona' => [
                'id_persona' => $persona->id_persona,
                'nombre' => $persona->nombre,
                'apellidos' => $persona->apellidos,
            ],
            'roles' => $roles
        ]);
    }

    // POST /api/registro/turista
    public function registroTurista(Request $request)
    {
        $request->validate([
            'nombre'   => 'required',
            'apellidos' => 'required',
            'correo'   => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8',
        ]);

        $usuario = Usuario::create([
            'correo' => $request->correo,
            'password' => bcrypt($request->password),
            'activo' => true,
            'estado' => 'aprobado',
            'correo_verificado' => 1,
        ]);

        $persona = \App\Models\Persona::create([
            'id_usuario' => $usuario->id_usuario,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
        ]);

        $rol = \App\Models\Rol::where('descripcion', 'turista')->first();
        $persona->roles()->attach($rol->id_rol);

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'persona' => $persona,
            'roles' => ['turista']
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
        $usuario = $request->user()->load('persona.roles');

        return response()->json([
            'success' => true,
            'correo' => $usuario->correo,
            'persona' => $usuario->persona,
            'roles' => $usuario->persona->roles->pluck('descripcion')
        ]);
    }

    // PUT /api/perfil
    public function actualizarPerfil(Request $request)
    {
        $user = $request->user();
        $persona = $user->persona;

        $request->validate([
            'nombre' => 'required|string|max:60',
            'apellidos' => 'required|string|max:120',
            'telefono' => 'nullable|string|max:20',
        ]);

        $persona->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
        ]);

        return response()->json(['success' => true]);
    }


    // GET /api/turista/pagos
    public function misPagos(Request $request)
    {
        $persona = $request->user()->persona;

        $pagos = DB::table('pago')
            ->join('paquete', 'pago.id_paquete', '=', 'paquete.id_paquete')
            ->join('destino', 'pago.id_destino', '=', 'destino.id_destino')
            ->where('pago.id_persona', $persona->id_persona)
            ->select(
                'pago.id_pago',
                'pago.monto',
                'pago.estado',
                'pago.fecha',
                'destino.nombre as destino_nombre',
                'paquete.nombre as paquete_nombre'
            )
            ->orderByDesc('pago.fecha')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pagos
        ]);
    }
    // DELETE /api/perfil
    public function eliminarCuenta(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
        $user->delete(); // cascade elimina persona

        return response()->json(['success' => true]);
    }
}
