<x-app-layout>
    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1600px] space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-700">SIGMA Agent</p>
                <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black tracking-tight text-slate-900">Inventario de equipos</h1>
                        <p class="mt-1 text-sm text-slate-500">Telemetría recibida desde los agentes SIGMA instalados.</p>
                    </div>
                    <a href="{{ route('assets.index') }}" class="text-sm font-bold text-blue-700 hover:text-blue-900">Inventario general</a>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach ([['Equipos administrados', $stats['total']], ['En línea', $stats['online']], ['Fuera de línea', $stats['offline']]] as [$label, $value])
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ number_format($value) }}</p>
                    </article>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <form method="GET" action="{{ route('admin.agent-inventory.index') }}" class="grid grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50 p-4 sm:grid-cols-[1fr_220px_auto] sm:items-end">
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Buscar equipo, serial, código o IP</span>
                        <input type="search" name="search" value="{{ $filters['search'] }}" maxlength="100" placeholder="Hostname, fabricante, BIOS, IP…" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Estado del agente</span>
                        <select name="status" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" @selected($filters['status'] === 'all')>Todos</option>
                            <option value="online" @selected($filters['status'] === 'online')>En línea</option>
                            <option value="offline" @selected($filters['status'] === 'offline')>Fuera de línea</option>
                        </select>
                    </label>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-blue-700 px-4 text-sm font-bold text-white hover:bg-blue-800">Filtrar</button>
                        @if ($filters['search'] !== '' || $filters['status'] !== 'all')
                            <a href="{{ route('admin.agent-inventory.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-bold text-slate-600 hover:bg-slate-100">Limpiar</a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1100px] text-left">
                        <thead class="bg-white text-[10px] font-extrabold uppercase tracking-widest text-slate-500">
                            <tr class="border-b border-slate-200">
                                <th class="px-5 py-4">Equipo</th>
                                <th class="px-5 py-4">Procesador</th>
                                <th class="px-5 py-4">RAM</th>
                                <th class="px-5 py-4">BIOS</th>
                                <th class="px-5 py-4">IPv4 principal</th>
                                <th class="px-5 py-4">Agente</th>
                                <th class="px-5 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($assets as $asset)
                                @php
                                    $processor = $asset->processors->first();
                                    $processorName = trim(($processor?->brand ?? $asset->cpu_brand ?? '').' '.($processor?->model ?? $asset->cpu_model ?? ''));
                                    $ramTotal = $asset->ramModules->isNotEmpty() ? $asset->ramModules->sum('capacity_gb') : $asset->ram_capacity_gb;
                                    $adapter = $asset->network?->adapters->firstWhere('is_primary', true) ?? $asset->network?->adapters->first();
                                @endphp
                                <tr class="align-top hover:bg-slate-50">
                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.agent-inventory.show', $asset) }}" class="font-bold text-blue-800 hover:underline">{{ $asset->hostname ?: 'Equipo sin hostname' }}</a>
                                        <div class="mt-1 text-xs text-slate-500">{{ $asset->manufacturer }} {{ $asset->model_version }}</div>
                                        <div class="mt-1 font-mono text-[11px] text-slate-400">{{ $asset->internal_code ?: $asset->serial_number ?: 'Sin código / serial' }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-700">{{ $processorName ?: 'Sin dato' }}<div class="mt-1 text-xs text-slate-500">{{ $processor?->cores ?? $asset->cpu_cores ?? '—' }} núcleos · {{ $processor?->threads ?? $asset->cpu_threads ?? '—' }} hilos · {{ $processor?->speed_mhz ?? $asset->cpu_speed_mhz ?? '—' }} MHz</div></td>
                                    <td class="px-5 py-4 text-slate-700">{{ $ramTotal !== null ? number_format((float) $ramTotal, 0).' GB' : ($asset->ram ?: 'Sin dato') }}<div class="mt-1 text-xs text-slate-500">{{ $asset->ramModules->count() }} módulos registrados</div></td>
                                    <td class="px-5 py-4 text-slate-700">{{ $asset->bios_manufacturer ?: 'Sin fabricante' }}<div class="mt-1 text-xs text-slate-500">{{ $asset->bios_version ?: 'Sin versión' }}</div></td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-700">{{ $adapter?->ipv4 ?: '—' }}</td>
                                    <td class="px-5 py-4 text-slate-700">{{ $asset->agent_version ?: '—' }}<div class="mt-1 text-xs text-slate-500">{{ $asset->agent_last_seen?->diffForHumans() ?: 'Sin heartbeat' }}</div></td>
                                    <td class="px-5 py-4">
                                        @if ($asset->is_online)
                                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">En línea</span>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">Fuera de línea</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">No hay equipos administrados por SIGMA Agent que coincidan con el filtro.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($assets->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">{{ $assets->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
