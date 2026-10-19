<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AgentInventoryController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', Rule::in(['all', 'online', 'offline'])],
        ]);

        $filters = [
            'search' => trim((string) ($validated['search'] ?? '')),
            'status' => $validated['status'] ?? 'all',
        ];

        $baseQuery = Asset::query()->where('is_agent_managed', true);
        $query = clone $baseQuery;

        if ($filters['search'] !== '') {
            $term = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $filters['search']).'%';
            $query->where(function ($assets) use ($term) {
                foreach (['hostname', 'serial_number', 'internal_code', 'manufacturer', 'model_version', 'agent_version', 'bios_serial', 'bios_manufacturer', 'bios_version', 'cpu_brand', 'cpu_model', 'windows_edition', 'windows_version', 'ram'] as $column) {
                    $assets->orWhereRaw($column.' ILIKE ?', [$term]);
                }

                $assets->orWhereHas('network.adapters', fn ($adapters) => $adapters->where('ipv4', 'ILIKE', $term));
            });
        }

        if ($filters['status'] === 'online') {
            $query->where('is_online', true);
        } elseif ($filters['status'] === 'offline') {
            $query->where(function ($assets) {
                $assets->where('is_online', false)->orWhereNull('is_online');
            });
        }

        $assets = $query
            ->with([
                'processors',
                'ramModules',
                'network.adapters',
            ])
            ->orderByRaw('LOWER(COALESCE(hostname, \'\')) ASC')
            ->orderBy('id')
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'online' => (clone $baseQuery)->where('is_online', true)->count(),
            'offline' => (clone $baseQuery)->where(function ($assets) {
                $assets->where('is_online', false)->orWhereNull('is_online');
            })->count(),
        ];

        return view('admin.agent_inventory.index', compact('assets', 'filters', 'stats'));
    }

    public function show(Asset $asset): View
    {
        abort_unless($asset->is_agent_managed, 404);

        $asset->load([
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'network.adapters',
            'battery',
            'monitors',
        ]);

        return view('admin.agent_inventory.show', compact('asset'));
    }

    public function updateRustDeskId(Request $request, Asset $asset): RedirectResponse
    {
        abort_unless($asset->is_agent_managed, 404);

        $validated = $request->validate([
            'rustdesk_id' => [
                'nullable',
                'string',
                'max:64',
                'regex:/^[A-Za-z0-9-]+$/',
                Rule::unique('assets', 'rustdesk_id')->ignore($asset->getKey()),
            ],
        ]);

        $asset->forceFill(['rustdesk_id' => $validated['rustdesk_id'] ?? null])->save();

        return redirect()
            ->route('admin.agent-inventory.show', $asset)
            ->with('status', 'Se actualizó el ID de RustDesk.');
    }
}
