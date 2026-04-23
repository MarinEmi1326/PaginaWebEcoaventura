<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $persona = $user->persona;
        $roles = $persona?->roles->pluck('descripcion')->toArray() ?? [];

        if (!in_array('admin_general', $roles)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}