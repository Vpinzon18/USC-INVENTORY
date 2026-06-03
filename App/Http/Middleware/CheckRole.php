<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     * El tercer parámetro 'roles' vendrá de la ruta.
     */
    // En tu archivo CheckRole.php
    public function handle(Request $request, Closure $next, int|string ...$roles): Response
    {
        // PHP ahora sabe que $roles es un array de enteros o strings
        if (Auth::check() && in_array((string)Auth::user()->role, array_map('strval', $roles))) {
            return $next($request);
        }

        abort(403, 'No tienes los permisos necesarios.');
    }
}
