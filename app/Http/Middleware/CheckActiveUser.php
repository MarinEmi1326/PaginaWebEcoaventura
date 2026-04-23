<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->activo) {
            Auth::logout();
            return redirect('/login')->with('error', 'Tu cuenta ha sido suspendida. No puedes acceder.');
        }
        return $next($request);
    }
}