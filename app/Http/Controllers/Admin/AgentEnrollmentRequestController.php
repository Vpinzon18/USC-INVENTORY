<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SigmaAgentEnrollmentRequest;
use App\Services\Inventory\Processors\DeviceProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AgentEnrollmentRequestController extends Controller
{
    public function index(): View
    {
        $filters = request()->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'status' => ['nullable', 'in:pending,approved,denied'],
        ]);

        $requestsQuery = SigmaAgentEnrollmentRequest::query()
            ->with('asset:id,hostname,serial_number')
            ->whereIn('status', ['pending', 'approved', 'denied']);

        if (!empty($filters['search'])) {
            $term = '%' . addcslashes($filters['search'], '%_\\') . '%';
            $requestsQuery->where(function ($query) use ($term) {
                $query->whereRaw('CAST(device AS TEXT) ILIKE ?', [$term])
                    ->orWhere('request_id', 'ILIKE', $term)
                    ->orWhere('request_ip', 'ILIKE', $term);
            });
        }

        if (!empty($filters['date_from'])) {
            $requestsQuery->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $requestsQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['status'])) {
            $requestsQuery->where('status', $filters['status']);
        }

        $requests = $requestsQuery
            ->latest('created_at')
            ->paginate(25)
            ->withQueryString();

        $summary = SigmaAgentEnrollmentRequest::query()
            ->whereIn('status', ['pending', 'approved', 'denied'])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.agent-enrollment-requests.index', compact('requests', 'summary', 'filters'));
    }

    public function approve(int $id, DeviceProcessor $deviceProcessor): RedirectResponse
    {
        $enrollment = DB::transaction(function () use ($id, $deviceProcessor) {
            $enrollment = SigmaAgentEnrollmentRequest::query()->lockForUpdate()->findOrFail($id);
            if ($enrollment->status !== 'pending' || $enrollment->expires_at->isPast()) {
                abort(409, 'La solicitud ya no está pendiente o venció.');
            }

            $asset = $deviceProcessor->process($enrollment->device);
            $issued = request()->user()->createToken(
                'sigma-agent-asset-' . $asset->getKey(),
                ['sigma-agent.asset.' . $asset->getKey(), 'sigma-agent.update']
            );

            $publicKey = openssl_pkey_get_public($enrollment->public_key);
            if (!$publicKey || !openssl_public_encrypt(
                $issued->plainTextToken,
                $encrypted,
                $publicKey,
                OPENSSL_PKCS1_OAEP_PADDING
            )) {
                $issued->accessToken->delete();
                abort(422, 'No se pudo cifrar la credencial para el equipo.');
            }

            $enrollment->update([
                'status' => 'approved',
                'asset_id' => $asset->getKey(),
                'approved_by_user_id' => request()->user()->getKey(),
                'encrypted_credential' => base64_encode($encrypted),
                'approved_at' => now(),
            ]);

            return $enrollment->fresh();
        }, 3);

        Log::notice('SIGMA Agent enrollment approved', [
            'request_id' => $enrollment->request_id,
            'asset_id' => $enrollment->asset_id,
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Equipo aprobado. SIGMA Agent recibirá su credencial automáticamente.');
    }

    /** Reissues an asset-scoped credential for a reinstalled agent on an existing asset. */
    public function recover(int $id, DeviceProcessor $deviceProcessor): RedirectResponse
    {
        try {
            $enrollment = DB::transaction(function () use ($id, $deviceProcessor) {
                $enrollment = SigmaAgentEnrollmentRequest::query()->lockForUpdate()->findOrFail($id);
                if ($enrollment->status !== 'pending' || $enrollment->expires_at->isPast()) {
                    abort(409, 'La solicitud ya no está pendiente o venció.');
                }

                $asset = $deviceProcessor->processForReenrollment($enrollment->device);
                $administrator = request()->user();
                $tokenName = 'sigma-agent-asset-' . $asset->getKey();
                $issued = $administrator->createToken(
                    $tokenName,
                    ['sigma-agent.asset.' . $asset->getKey(), 'sigma-agent.update']
                );

                $publicKey = openssl_pkey_get_public($enrollment->public_key);
                if (!$publicKey || !openssl_public_encrypt(
                    $issued->plainTextToken,
                    $encrypted,
                    $publicKey,
                    OPENSSL_PKCS1_OAEP_PADDING
                )) {
                    abort(422, 'No se pudo cifrar la credencial para el equipo.');
                }

                // Revoke previous credentials for this asset only after the new credential is ready.
                DB::table('personal_access_tokens')
                    ->where('tokenable_type', $administrator->getMorphClass())
                    ->where('name', $tokenName)
                    ->where('id', '<>', $issued->accessToken->getKey())
                    ->delete();

                $enrollment->update([
                    'status' => 'approved',
                    'asset_id' => $asset->getKey(),
                    'approved_by_user_id' => $administrator->getKey(),
                    'encrypted_credential' => base64_encode($encrypted),
                    'approved_at' => now(),
                ]);

                return $enrollment->fresh();
            }, 3);
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?? 'No fue posible recuperar el agente.';
            return back()->with('error', $message);
        }

        Log::notice('SIGMA Agent credential recovered for existing asset', [
            'request_id' => $enrollment->request_id,
            'asset_id' => $enrollment->asset_id,
            'recovered_by' => auth()->id(),
            'request_ip' => $enrollment->request_ip,
            'new_agent_id' => $enrollment->device['agentId'] ?? null,
        ]);

        return back()->with('success', 'Agente recuperado en el activo existente #' . $enrollment->asset_id . '. Se emitió una credencial nueva y se revocaron las credenciales anteriores de ese activo.');
    }

    public function deny(int $id): RedirectResponse
    {
        $enrollment = SigmaAgentEnrollmentRequest::query()->findOrFail($id);
        if ($enrollment->status !== 'pending') {
            return back()->with('error', 'La solicitud ya fue procesada.');
        }

        $enrollment->update(['status' => 'denied']);
        Log::notice('SIGMA Agent enrollment denied', [
            'request_id' => $enrollment->request_id,
            'denied_by' => auth()->id(),
        ]);

        return back()->with('success', 'Solicitud rechazada.');
    }
}
