<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Services\Inventory\InventoryProcessor;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
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
            'hardwareHash' => ['sometimes', 'nullable', 'string', 'max:64'],
            'inventoryHash' => ['sometimes', 'nullable', 'string', 'max:64'],
            'agentVersion' => ['sometimes', 'nullable', 'string', 'max:20'],
            'inventoryVersion' => ['sometimes', 'nullable', 'string', 'max:20'],
        ]);

        try {
            return $processor->process($request);
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
    public function heartbeat(Request $request)
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
