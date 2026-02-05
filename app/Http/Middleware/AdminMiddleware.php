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

        // tu usuario tiene columna rol en tabla usuario
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
