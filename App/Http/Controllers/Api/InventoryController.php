<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Services\Inventory\InventoryProcessor;

class InventoryController extends Controller
{
    /**
     * Recibe el inventario completo enviado por SIGMA Agent.
     */
    public function report(
        Request $request,
        InventoryProcessor $processor
    ) {
        return $processor->process($request);
    }

    /**
     * Recibe el heartbeat del SIGMA Agent.
     */
    public function heartbeat(Request $request)
    {
        $request->validate([
            'serialNumber' => 'required|string',
        ]);

        $asset = Asset::where(
            'serial_number',
            $request->serialNumber
        )->first();

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Activo no encontrado.',
                'serial_number' => $request->serialNumber,
            ], 404);
        }

        $asset->update([
            'agent_last_seen' => now(),
            'is_online' => true,
            'agent_version' => $request->agentVersion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat recibido correctamente.',
            'data' => [
                'serial_number' =>
                    $asset->serial_number,

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