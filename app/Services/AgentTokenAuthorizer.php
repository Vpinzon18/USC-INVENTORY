<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AgentTokenAuthorizer
{
    public function authorizeAsset(Request $request, Asset $asset): void
    {
        $token = $request->user()?->currentAccessToken();
        $requiredAbility = 'sigma-agent.asset.' . $asset->getKey();

        if (!$token instanceof PersonalAccessToken ||
            (!$this->hasExactAbility($token, $requiredAbility) &&
                !$this->legacyTokenAllowed($token))) {
            throw new AuthorizationException(
                'El token del agente no está autorizado para este equipo.'
            );
        }
    }

    public function authorizeAbility(Request $request, string $ability): void
    {
        $token = $request->user()?->currentAccessToken();

        if (!$token instanceof PersonalAccessToken ||
            (!$this->hasExactAbility($token, $ability) &&
                !$this->legacyTokenAllowed($token))) {
            throw new AuthorizationException(
                'El token del agente no tiene el alcance requerido.'
            );
        }
    }

    private function hasExactAbility(PersonalAccessToken $token, string $ability): bool
    {
        return in_array($ability, $token->abilities ?? [], true);
    }

    private function legacyTokenAllowed(PersonalAccessToken $token): bool
    {
        return config('sigma-agent.allow_legacy_wildcard_tokens', true) &&
            $token->name === 'Usuario Agente API' &&
            ($token->abilities ?? []) === ['*'];
    }
}
