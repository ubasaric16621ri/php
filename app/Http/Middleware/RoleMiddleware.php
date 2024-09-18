<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Proveri da li korisnik ima odgovarajuću ulogu
        if (!$request->user() || $request->user()->role !== $role) {
            return redirect('/home');
        }

        return $next($request);
    }
}
