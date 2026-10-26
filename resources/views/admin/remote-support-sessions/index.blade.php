<x-app-layout>
    <div class="mx-auto max-w-[98%] px-3 py-6 sm:px-5">
        <div class="mb-5 rounded-2xl border border-violet-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[.16em] text-violet-700">SIGMA · Auditoría</p>
            <h1 class="mt-1 text-2xl font-black text-slate-900">Sesiones de asistencia remota</h1>
            <p class="mt-1 text-sm text-slate-600">Solicitudes, autorizaciones, equipo origen y destino, horas e IP observadas por SIGMA.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Usuario</th>
                            <th class="px-4 py-3">Origen</th>
                            <th class="px-4 py-3">Equipo destino</th>
                            <th class="px-4 py-3">Estado / alcance</th>
                            <th class="px-4 py-3">Inicio / fin</th>
                            <th class="px-4 py-3">IP registrada</th>
                            <th class="px-4 py-3">Eventos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($sessions as $session)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-semibold">{{ $session->requester?->name ?? 'Usuario eliminado' }}</div>
                                    <div class="text-xs text-slate-500">{{ $session->requester?->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>{{ $session->sourceAsset?->hostname ?: $session->sourceAsset?->internal_code ?: 'Sin vincular' }}</div>
                                    <div class="font-mono text-xs text-slate-500">{{ $session->source_rustdesk_id ?: '—' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>{{ $session->targetAsset?->hostname ?: $session->targetAsset?->internal_code ?: 'Activo #' . $session->target_asset_id }}</div>
                                    <div class="font-mono text-xs text-slate-500">{{ $session->targetAsset?->rustdesk_id ?: '—' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-bold">{{ ucfirst($session->status) }}</span>
                                    <div class="mt-1 text-xs text-slate-500">{{ implode(', ', $session->scopes ?? []) }}</div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-xs">
                                    <div>Inicio: {{ $session->started_at?->timezone(config('app.timezone'))->format('d/m/Y H:i:s') ?: '—' }}</div>
                                    <div>Fin: {{ $session->ended_at?->timezone(config('app.timezone'))->format('d/m/Y H:i:s') ?: '—' }}</div>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">
                                    <div>Origen: {{ $session->source_ip ?: '—' }}</div>
                                    <div>Destino: {{ $session->target_ip ?: '—' }}</div>
                                    <div>Solicitud: {{ $session->requested_ip ?: '—' }}</div>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @foreach ($session->events as $event)
                                        <div>{{ $event->created_at?->timezone(config('app.timezone'))->format('H:i:s') }} · {{ $event->event }}</div>
                                    @endforeach
                                    @if ($session->close_reason)
                                        <div class="mt-1 text-slate-500">Cierre: {{ $session->close_reason }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">No hay sesiones de asistencia registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">{{ $sessions->links() }}</div>
        </div>
    </div>
</x-app-layout>
