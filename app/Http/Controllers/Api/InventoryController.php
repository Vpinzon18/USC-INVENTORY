<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Asset;
use App\Models\SigmaAgentEnrollmentCode;
use App\Models\User;
use App\Services\Inventory\Processors\DeviceProcessor;
use App\Services\Inventory\InventoryProcessor;
use App\Services\AgentTokenAuthorizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    /** Exchange an administrator-issued, one-use code for an asset-scoped token. */
    public function enroll(Request $request, DeviceProcessor $deviceProcessor)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]{64}$/'],
            'device' => ['required', 'array'],
            'device.serialNumber' => ['sometimes', 'nullable', 'string', 'max:100'],
            'device.hostname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'device.manufacturer' => ['sometimes', 'nullable', 'string', 'max:100'],
            'device.model' => ['sometimes', 'nullable', 'string', 'max:255'],
            'device.uuid' => ['sometimes', 'nullable', 'uuid'],
        ]);

        $device = $validated['device'];
        if (!collect(['serialNumber', 'uuid', 'hostname'])->contains(
            fn (string $key) => filled($device[$key] ?? null)
        )) {
            return response()->json([
                'success' => false,
                'message' => 'El equipo debe proporcionar serial, UUID de hardware o nombre de equipo.',
            ], 422);
        }

        $codeHash = hash('sha256', $validated['code']);

        return DB::transaction(function () use ($codeHash, $device, $deviceProcessor) {
            $enrollment = SigmaAgentEnrollmentCode::query()
                ->where('code_hash', $codeHash)
                ->lockForUpdate()
                ->first();

            if (!$enrollment || $enrollment->consumed_at || $enrollment->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código de enrolamiento no es válido, ya fue usado o venció.',
                ], 403);
            }

            $administrator = User::query()
                ->whereKey($enrollment->created_by_user_id)
                ->where('role', 1)
                ->first();

            if (!$administrator) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay un administrador habilitado para emitir la credencial.',
                ], 503);
            }

            $asset = $deviceProcessor->process($device);
            $assetId = $asset->getKey();
            $issued = $administrator->createToken(
                'sigma-agent-asset-' . $assetId,
                ['sigma-agent.asset.' . $assetId]
            );

            $enrollment->update([
                'asset_id' => $assetId,
                'consumed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'token' => $issued->plainTextToken,
                'assetId' => $assetId,
                'agentId' => $asset->agent_uuid,
            ]);
        }, 3);
    }

    /**
     * Recibe el inventario completo enviado por SIGMA Agent.
     */
    public function report(
        Request $request,
        InventoryProcessor $processor
    ) {
        $request->validate([
            'device' => ['required', 'array'],
            'device.assetId' => ['sometimes', 'nullable', 'integer', 'min:1', 'exists:assets,id'],
            'device.agentId' => ['sometimes', 'nullable', 'uuid'],
            'device.serialNumber' => ['sometimes', 'nullable', 'string', 'max:100'],
            'device.hostname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'device.model' => ['sometimes', 'nullable', 'string', 'max:255'],
            'device.manufacturer' => ['sometimes', 'nullable', 'string', 'max:100'],
            'device.uuid' => ['sometimes', 'nullable', 'uuid'],
            'network' => ['sometimes', 'array'],
            'cpu' => ['sometimes', 'array'],
            'gpus' => ['sometimes', 'array', 'max:32'],
            'ram' => ['sometimes', 'array'],
            'ram.modules' => ['sometimes', 'array', 'max:64'],
            'storageDevices' => ['sometimes', 'array', 'max:128'],
            'windows' => ['sometimes', 'array'],
            'bios' => ['sometimes', 'array'],
            'security' => ['sometimes', 'array'],
            'user' => ['sometimes', 'array'],
            'monitors' => ['sometimes', 'array', 'max:32'],
            'battery' => ['sometimes', 'array'],
            'motherboard' => ['sometimes', 'array'],
            'software' => ['sometimes', 'array', 'max:5000'],
            'rustDeskId' => ['sometimes', 'nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9-]+$/'],
            'hardwareHash' => ['sometimes', 'nullable', 'string', 'max:64'],
            'inventoryHash' => ['sometimes', 'nullable', 'string', 'max:64'],
            'agentVersion' => ['sometimes', 'nullable', 'string', 'max:20'],
            'inventoryVersion' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        try {
            return $processor->process($request);
        } catch (AuthorizationException $exception) {
            throw $exception;
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo resolver la identidad del equipo.',
                'errors' => $exception->errors(),
            ], 422);
        } catch (\Throwable $exception) {
            Log::error('Error procesando inventario de SIGMA Agent.', [
                'user_id' => $request->user()?->getAuthIdentifier(),
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No fue posible procesar el inventario.',
            ], 500);
        }
    }

    /**
     * Recibe el heartbeat del SIGMA Agent.
     */
    public function heartbeat(Request $request, AgentTokenAuthorizer $tokenAuthorizer)
    {
        $validated = $request->validate([
            'assetId' => 'required_without:serialNumber|integer|min:1|exists:assets,id',
            'serialNumber' => 'required_without:assetId|nullable|string|max:100',
            'agentVersion' => 'sometimes|nullable|string|max:20',
        ]);

        $asset = isset($validated['assetId'])
            ? Asset::find($validated['assetId'])
            : null;

        if (!$asset && isset($validated['serialNumber'])) {
            $matches = Asset::where('serial_number', $validated['serialNumber'])
                ->limit(2)
                ->get();

            if ($matches->count() > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'El serial está duplicado. Envíe assetId en el heartbeat.',
                ], 409);
            }

            $asset = $matches->first();
        }

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Activo no encontrado.',
                'serial_number' => $validated['serialNumber'] ?? null,
            ], 404);
        }

        $tokenAuthorizer->authorizeAsset($request, $asset);

        $updates = [
            'agent_last_seen' => now(),
            'is_online' => true,
            'is_agent_managed' => true,
        ];

        if (array_key_exists('agentVersion', $validated)) {
            $updates['agent_version'] = $validated['agentVersion'];
        }

        $asset->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat recibido correctamente.',
            'data' => [
                'serial_number' =>
                    $asset->serial_number,

                'asset_id' =>
                    $asset->id,

                'agent_id' =>
                    $asset->agent_uuid,

                'agent_last_seen' =>
                    $asset->agent_last_seen,

                'is_online' =>
                    $asset->is_online,

                'agent_version' =>
                    $asset->agent_version,
            ],
        ]);
    }
}
