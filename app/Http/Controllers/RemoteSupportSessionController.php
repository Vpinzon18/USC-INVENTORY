<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\RemoteSupportSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;

class RemoteSupportSessionController extends Controller
{
    public function index(Request $request): View
    {
        $query = RemoteSupportSession::query()
            ->with([
                'requester:id,name,email',
                'sourceAsset:id,internal_code,hostname,rustdesk_id',
                'targetAsset:id,internal_code,hostname,rustdesk_id',
                'events',
            ])
            ->latest('id');

        if ((int) $request->user()->role !== 1) {
            $query->where('requested_by_user_id', $request->user()->getKey());
        }

        return view('admin.remote-support-sessions.index', [
            'sessions' => $query->paginate(50)->withQueryString(),
        ]);
    }

    public function create(Request $request, Asset $asset): JsonResponse
    {
        abort_unless(in_array((int) $request->user()->role, [1, 2], true), 403);
        abort_unless(filled($asset->rustdesk_id) && $asset->is_agent_managed, 422,
            'El activo debe tener RustDesk asociado y un agente SIGMA activo.');

        $plainRequest = bin2hex(random_bytes(32));
        $session = RemoteSupportSession::query()->create([
            'requested_by_user_id' => $request->user()->getKey(),
            'target_asset_id' => $asset->getKey(),
            'request_hash' => hash('sha256', $plainRequest),
            'status' => 'pending',
            'scopes' => ['desktop'],
            'requested_ip' => $request->ip(),
            'expires_at' => now()->addMinutes(2),
        ]);
        $session->events()->create([
            'event' => 'requested',
            'actor' => 'user',
            'details' => ['role' => (int) $request->user()->role, 'scope' => 'desktop'],
        ]);

        Log::info('SIGMA remote support requested', [
            'session' => $session->getKey(),
            'operator' => $request->user()->getKey(),
            'target_asset' => $asset->getKey(),
        ]);

        return response()->json([
            'success' => true,
            'launchUrl' => 'rustdesk://' . rawurlencode($asset->rustdesk_id)
                . '?sigma_request=' . $plainRequest,
            'expiresAt' => $session->expires_at->toIso8601String(),
        ], 201)->header('Cache-Control', 'no-store');
    }

    public function redeem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'requestId' => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]{64}$/'],
            'targetId' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9-]+$/'],
            'operatorUpn' => ['required', 'string', 'max:255'],
        ]);

        $agentAsset = $this->agentAsset($request);
        if (!filled($agentAsset->rustdesk_id)) {
            return $this->deny($request, null, 'redeem', 'source_rustdesk_id_missing', 403);
        }

        return DB::transaction(function () use ($data, $agentAsset, $request) {
            $session = RemoteSupportSession::query()
                ->where('request_hash', hash('sha256', $data['requestId']))
                ->lockForUpdate()
                ->first();

            if (!$session || $session->status !== 'pending' || $session->expires_at->isPast()) {
                return $this->deny($request, $session, 'redeem', 'invalid_or_expired_request', 410);
            }

            $operator = $session->requester;
            if (!in_array((int) $operator->role, [1, 2], true) || !filled($operator->domain)) {
                return $this->deny($request, $session, 'redeem', 'domain_user_role_denied', 403);
            }
            if (!$this->matchesOperatorAccount($operator, $data['operatorUpn'])) {
                return $this->deny($request, $session, 'redeem', 'windows_user_mismatch', 403);
            }
            if ((int) $agentAsset->getKey() === (int) $session->target_asset_id) {
                return $this->deny($request, $session, 'redeem', 'source_equals_target', 403);
            }
            if (!$session->targetAsset->rustdesk_id
                || !hash_equals($session->targetAsset->rustdesk_id, $data['targetId'])) {
                return $this->deny($request, $session, 'redeem', 'target_peer_mismatch', 403);
            }

            $plainGrant = bin2hex(random_bytes(32));
            $session->forceFill([
                'source_asset_id' => $agentAsset->getKey(),
                'source_rustdesk_id' => $agentAsset->rustdesk_id,
                'source_ip' => $request->ip(),
                'grant_hash' => hash('sha256', $plainGrant),
                'status' => 'authorized',
                'authorized_at' => now(),
                'expires_at' => now()->addMinutes(10),
            ])->save();
            $session->events()->create([
                'event' => 'authorized',
                'actor' => 'agent',
                'details' => ['source_asset_id' => $agentAsset->getKey(), 'scope' => 'desktop'],
            ]);

            Log::info('SIGMA remote support authorized', [
                'session' => $session->getKey(),
                'operator' => $session->requested_by_user_id,
                'source_asset' => $agentAsset->getKey(),
                'target_asset' => $session->target_asset_id,
            ]);

            return response()->json([
                'success' => true,
                'grant' => $plainGrant,
                'targetRustDeskId' => $session->targetAsset->rustdesk_id,
                'scope' => 'desktop',
                'expiresAt' => $session->expires_at->toIso8601String(),
            ])->header('Cache-Control', 'no-store');
        }, 3);
    }

    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'grant' => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]{64}$/'],
            'peerId' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9-]+$/'],
            'sessionId' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
        ]);
        $agentAsset = $this->agentAsset($request);

        return DB::transaction(function () use ($data, $agentAsset, $request) {
            $session = RemoteSupportSession::query()
                ->where('grant_hash', hash('sha256', $data['grant']))
                ->lockForUpdate()
                ->first();

            if (!$session || !in_array($session->status, ['authorized', 'active'], true)
                || $session->expires_at->isPast()) {
                return $this->deny($request, $session, 'verify', 'invalid_or_expired_grant', 403);
            }
            if ((int) $session->target_asset_id !== (int) $agentAsset->getKey()) {
                return $this->deny($request, $session, 'verify', 'wrong_target_asset', 403);
            }
            if (!in_array('desktop', $session->scopes ?? [], true)) {
                return $this->deny($request, $session, 'verify', 'scope_denied', 403);
            }
            if ($session->source_rustdesk_id === null
                || !hash_equals($session->source_rustdesk_id, $data['peerId'])) {
                return $this->deny($request, $session, 'verify', 'source_peer_mismatch', 403);
            }
            $operator = $session->requester;
            if (!in_array((int) $operator->role, [1, 2], true) || !filled($operator->domain)) {
                return $this->deny($request, $session, 'verify', 'role_revoked', 403);
            }
            $source = $session->sourceAsset;
            if (!$source || !$source->is_agent_managed || !$source->rustdesk_id
                || !hash_equals($session->source_rustdesk_id, $source->rustdesk_id)) {
                return $this->deny($request, $session, 'verify', 'source_asset_identity_changed', 403);
            }
            if ($session->client_session_id !== null
                && !hash_equals($session->client_session_id, $data['sessionId'])) {
                return $this->deny($request, $session, 'verify', 'session_replay_denied', 403);
            }

            if ($session->status === 'authorized') {
                $session->forceFill([
                    'status' => 'active',
                    'started_at' => now(),
                    'target_ip' => $request->ip(),
                    'client_session_id' => $data['sessionId'],
                ])->save();
                $session->events()->create([
                    'event' => 'started',
                    'actor' => 'target_agent',
                    'details' => ['peer_id' => $data['peerId'], 'scope' => 'desktop'],
                ]);
                Log::info('SIGMA remote support started', [
                    'session' => $session->getKey(),
                    'operator' => $session->requested_by_user_id,
                    'source_asset' => $session->source_asset_id,
                    'target_asset' => $session->target_asset_id,
                ]);
            }

            return response()->json(['success' => true, 'sessionId' => $session->getKey()]);
        }, 3);
    }

    public function finish(Request $request): JsonResponse
    {
        $data = $request->validate([
            'grant' => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]{64}$/'],
            'sessionId' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'reason' => ['nullable', 'string', 'max:120'],
        ]);
        $agentAsset = $this->agentAsset($request);

        $session = RemoteSupportSession::query()
            ->where('grant_hash', hash('sha256', $data['grant']))
            ->where('target_asset_id', $agentAsset->getKey())
            ->where('client_session_id', $data['sessionId'])
            ->where('status', 'active')
            ->first();

        if ($session) {
            $reason = preg_replace('/[^a-zA-Z0-9 _.-]/', '', $data['reason'] ?? 'closed') ?: 'closed';
            $session->forceFill(['status' => 'ended', 'ended_at' => now(), 'close_reason' => $reason])->save();
            $session->events()->create([
                'event' => 'ended',
                'actor' => 'target_agent',
                'details' => ['reason' => $reason],
            ]);
            Log::info('SIGMA remote support ended', ['session' => $session->getKey(), 'reason' => $reason]);
        }

        return response()->json(['success' => true]);
    }

    private function agentAsset(Request $request): Asset
    {
        $token = $request->user()?->currentAccessToken();
        abort_unless($token instanceof PersonalAccessToken, 403, 'Se requiere credencial de agente.');

        $assetIds = [];
        foreach ($token->abilities ?? [] as $ability) {
            if (preg_match('/^sigma-agent\.asset\.(\d+)$/', $ability, $matches)) {
                $assetIds[] = (int) $matches[1];
            }
        }
        $assetIds = array_values(array_unique($assetIds));
        abort_unless(count($assetIds) === 1, 403, 'La credencial debe estar limitada a un activo específico.');

        $asset = Asset::query()->findOrFail($assetIds[0]);
        abort_unless($asset->is_agent_managed, 403, 'El agente no está habilitado para este activo.');
        return $asset;
    }

    private function matchesOperatorAccount(\App\Models\User $operator, string $account): bool
    {
        $account = mb_strtolower(trim($account));
        $email = mb_strtolower(trim((string) $operator->email));
        if (hash_equals($email, $account)) {
            return true;
        }

        [$domain, $samAccountName] = array_pad(explode('\\', $account, 2), 2, '');
        if ($domain === '' || $samAccountName === '') {
            return false;
        }

        $emailName = strstr($email, '@', true) ?: '';
        $emailDomain = strstr($email, '@') ?: '';
        $storedDomain = mb_strtolower(trim((string) $operator->domain));
        $domainMatches = $storedDomain !== '' && (
            $domain === $storedDomain ||
            $emailDomain === '@' . $storedDomain ||
            str_starts_with($storedDomain, $domain . '.')
        );

        return $domainMatches && $emailName !== '' && hash_equals($emailName, $samAccountName);
    }

    private function deny(Request $request, ?RemoteSupportSession $session, string $stage, string $reason, int $status): JsonResponse
    {
        if ($session) {
            if (in_array($session->status, ['pending', 'authorized'], true)) {
                $session->forceFill(['status' => 'denied'])->save();
            }
            $session->events()->create([
                'event' => 'denied',
                'actor' => 'agent',
                'details' => ['stage' => $stage, 'reason' => $reason],
            ]);
        }
        Log::warning('SIGMA remote support denied', [
            'session' => $session?->getKey(),
            'stage' => $stage,
            'reason' => $reason,
            'source_ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'SIGMA denegó la autorización remota.',
            'code' => $reason,
        ], $status);
    }
}
