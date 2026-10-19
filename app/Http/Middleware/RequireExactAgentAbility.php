<?php

namespace App\Http\Middleware;

use App\Services\AgentTokenAuthorizer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireExactAgentAbility
{
    public function __construct(private AgentTokenAuthorizer $authorizer)
    {
    }

    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $this->authorizer->authorizeAbility($request, $ability);

        return $next($request);
    }
}
