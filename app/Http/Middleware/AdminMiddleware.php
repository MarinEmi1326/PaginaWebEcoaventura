<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // ============================================
        // CORREGIDO: Obtener roles desde persona
        // ============================================
        $user = auth()->user();
        $persona = $user->persona;
        $roles = $persona?->roles->pluck('descripcion')->toArray() ?? [];

        if (!in_array('admin_general', $roles)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}