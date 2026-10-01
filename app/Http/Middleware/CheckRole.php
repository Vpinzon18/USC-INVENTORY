<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{

    public function handle(Request $request, Closure $next, int|string ...$roles): Response
    {
        if (Auth::check() && in_array((string)Auth::user()->role, array_map('strval', $roles))) {
            return $next($request);
        }

        abort(403, 'No tienes los permisos necesarios.');
    }
}
