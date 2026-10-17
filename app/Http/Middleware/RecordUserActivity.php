<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecordUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $userBeforeRequest = $request->user();
        $response = $next($request);
        $user = $userBeforeRequest ?? $request->user() ?? Auth::user();
        $route = $request->route();
        $routeName = $route?->getName();
        $method = strtoupper($request->method());

        if (!$user || !$routeName || in_array($method, ['HEAD', 'OPTIONS'], true)) {
            return $response;
        }

        // Filter dropdown requests are background lookups rather than user actions.
        if (str_starts_with($routeName, 'api.filters.') || $routeName === 'admin.audit-logs.index') {
            return $response;
        }

        try {
            $action = match (true) {
                $routeName === 'login.post' => 'login',
                $routeName === 'logout' => 'logout',
                $method === 'GET' && preg_match('/(export|download|preview)/i', $routeName) === 1 => 'exported',
                $method === 'GET' => 'viewed',
                in_array($method, ['POST'], true) => 'created',
                in_array($method, ['PUT', 'PATCH'], true) => 'updated',
                $method === 'DELETE' => 'deleted',
                default => 'action',
            };

            [$subjectType, $subjectId] = $this->subjectFromRoute($route);

            AuditLog::create([
                'user_id' => $user->getAuthIdentifier(),
                'user_name' => $user->name,
                'user_email' => $user->email,
                'action' => $action,
                'route_name' => $routeName,
                'method' => $method,
                'path' => $route->uri(),
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'details' => $this->safeDetails($request, $method),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 1000, '') : null,
                'status_code' => $response->getStatusCode(),
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            // An audit write must never interrupt the requested business action.
            Log::error('No fue posible registrar la actividad del usuario.', [
                'route' => $routeName,
                'exception' => $exception->getMessage(),
            ]);
        }

        return $response;
    }

    private function subjectFromRoute(mixed $route): array
    {
        foreach ($route?->parameters() ?? [] as $key => $value) {
            if ($value instanceof Model) {
                return [$value->getMorphClass(), (string) $value->getKey()];
            }

            if (is_scalar($value) && preg_match('/(^id$|_id$)/i', (string) $key) === 1) {
                return [null, (string) $value];
            }
        }

        return [null, null];
    }

    private function safeDetails(Request $request, string $method): ?array
    {
        if ($method === 'GET') {
            return null;
        }

        $details = $this->sanitize($request->except(['_token', '_method']));

        return $details === [] ? null : $details;
    }

    private function sanitize(array $values): array
    {
        $safe = [];

        foreach (array_slice($values, 0, 50, true) as $key => $value) {
            if (preg_match('/password|token|secret|authorization|cookie|api.?key|private.?key|file|image|signature|remember/i', (string) $key)) {
                continue;
            }

            if (is_array($value)) {
                $safe[$key] = $this->sanitize($value);
            } elseif (is_bool($value) || is_int($value) || is_float($value) || $value === null) {
                $safe[$key] = $value;
            } elseif (is_string($value)) {
                $safe[$key] = Str::limit($value, 500, '');
            }
        }

        return $safe;
    }
}
