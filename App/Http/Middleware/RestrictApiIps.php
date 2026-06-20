<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictApiIps
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Define las IPs autorizadas (Tu entorno local + Red de la USC)
        $allowedIps = [
            '172.18.20.96',     // Para tus pruebas locales
        ];

        // 2. Si la IP que intenta entrar no está en la lista, bloqueamos la puerta
        if (!in_array($request->ip(), $allowedIps)) {
            return response()->json([
                'error' => 'Acceso denegado',
                'message' => 'Tu IP (' . $request->ip() . ') no está autorizada por la red institucional de la USC.'
            ], 403);
        }

        // 3. Si la IP es válida, lo dejamos seguir hacia el controlador
        return $next($request);
    }
}