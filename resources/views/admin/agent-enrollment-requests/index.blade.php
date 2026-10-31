<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-[96%] max-w-[1600px] space-y-6">
            <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-600"></div>
                <div class="flex items-center gap-4 pl-2">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75 11.25 15 15 9.75M12 3l2.25 1.5 2.7-.15 1.2 2.4 2.4 1.2-.15 2.7L21.9 13l-1.5 2.25.15 2.7-2.4 1.2-1.2 2.4-2.7-.15L12 22.9l-2.25-1.5-2.7.15-1.2-2.4-2.4-1.2.15-2.7L2.1 13l1.5-2.25-.15-2.7 2.4-1.2 1.2-2.4 2.7.15L12 3Z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-700">Administración · Agentes SIGMA</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Solicitudes de agentes</h1>
                        <p class="mt-1 max-w-4xl text-sm text-slate-500">Verifica la identidad del equipo antes de autorizarlo. Si el agente fue reinstalado, recupera el vínculo para emitir una nueva credencial al activo existente.</p>
                    </div>
                </div>
            </section>

            @if (session('success'))
                <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
                    <span class="mt-0.5 text-lg leading-none">✓</span><span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800 shadow-sm">
                    <span class="mt-0.5 text-lg leading-none">!</span><span>{{ session('error') }}</span>
                </div>
            @endif

            <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Resumen de solicitudes">
                <article class="rounded-2xl border border-amber-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Pendientes</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">◷</span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-amber-600">{{ number_format((int) ($summary['pending'] ?? 0)) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Requieren revisión del administrador</p>
                </article>
                <article class="rounded-2xl border border-emerald-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Aprobadas</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">✓</span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600">{{ number_format((int) ($summary['approved'] ?? 0)) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Agentes autorizados por SIGMA</p>
                </article>
                <article class="rounded-2xl border border-rose-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Rechazadas</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600">×</span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-rose-600">{{ number_format((int) ($summary['denied'] ?? 0)) }}</p>
                    <p class="mt-1 text-xs text-slate-500">Solicitudes no autorizadas</p>
                </article>
                <article class="rounded-2xl border border-blue-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">En esta página</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">▤</span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-blue-700">{{ number_format($requests->count()) }}</p>
                    <p class="mt-1 text-xs text-slate-500">De {{ number_format($requests->total()) }} solicitudes registradas</p>
                </article>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-wide text-slate-800">Bandeja de solicitudes</h2>
                        <p class="mt-1 text-xs text-slate-500">Ordenadas desde la más reciente · Máximo 25 resultados por página</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-extrabold text-blue-700">{{ number_format($requests->total()) }} REGISTROS</span>
                </div>

                <form method="GET" action="{{ route('admin.agent-enrollment-requests.index') }}" class="grid grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50/70 p-4 sm:grid-cols-2 xl:grid-cols-6 xl:items-end">
                    <label class="block xl:col-span-2">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Buscar equipo</span>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" placeholder="Hostname, serial, modelo, IP o solicitud" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Desde</span>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Hasta</span>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Estado</span>
                        <select name="status" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pendiente</option>
                            <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Aprobada</option>
                            <option value="denied" @selected(($filters['status'] ?? '') === 'denied')>Rechazada</option>
                        </select>
                    </label>
                    <div class="flex flex-wrap gap-2 xl:justify-end">
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-extrabold text-white shadow-sm transition hover:bg-blue-700">Buscar</button>
                        @if (filled($filters['search'] ?? null) || filled($filters['date_from'] ?? null) || filled($filters['date_to'] ?? null) || filled($filters['status'] ?? null))
                            <a href="{{ route('admin.agent-enrollment-requests.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-100">Limpiar</a>
                        @endif
                    </div>
                    @error('search')<p class="text-xs font-medium text-rose-600 sm:col-span-2 xl:col-span-6">La búsqueda no puede superar 100 caracteres.</p>@enderror
                    @error('date_from')<p class="text-xs font-medium text-rose-600">Ingresa una fecha inicial válida.</p>@enderror
                    @error('date_to')<p class="text-xs font-medium text-rose-600">La fecha final debe ser válida e igual o posterior a la inicial.</p>@enderror
                    @error('status')<p class="text-xs font-medium text-rose-600">Selecciona un estado válido.</p>@enderror
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left">
                        <thead>
                            <tr class="border-b border-slate-200 bg-white text-[10px] font-extrabold uppercase tracking-widest text-slate-500">
                                <th class="px-5 py-4">Equipo</th>
                                <th class="px-5 py-4">Fabricante / modelo</th>
                                <th class="px-5 py-4">Serial / solicitud</th>
                                <th class="px-5 py-4">Origen</th>
                                <th class="px-5 py-4">Estado / activo SIGMA</th>
                                <th class="px-5 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($requests as $enrollment)
                                @php
                                    $isActionable = $enrollment->status === 'pending' && $enrollment->expires_at->isFuture();
                                    $statusLabel = $enrollment->status === 'pending'
                                        ? ($isActionable ? 'Pendiente' : 'Vencida')
                                        : ($enrollment->status === 'approved' ? 'Aprobada' : 'Rechazada');
                                    $statusClass = $enrollment->status === 'pending'
                                        ? ($isActionable ? 'bg-amber-50 text-amber-700 ring-amber-200' : 'bg-slate-100 text-slate-600 ring-slate-200')
                                        : ($enrollment->status === 'approved' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-rose-200');
                                @endphp
                                <tr class="align-top transition-colors hover:bg-blue-50/30">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-900">{{ $enrollment->device['hostname'] ?? 'Sin hostname' }}</div>
                                        <div class="mt-1 text-xs text-slate-400">Recibida {{ $enrollment->created_at?->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">{{ trim(($enrollment->device['manufacturer'] ?? '') . ' ' . ($enrollment->device['model'] ?? '')) ?: 'Sin dato' }}</td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-700">{{ $enrollment->device['serialNumber'] ?? 'Sin serial' }}</div>
                                        <div class="mt-1 max-w-[230px] truncate font-mono text-[10px] text-slate-400" title="{{ $enrollment->request_id }}">{{ $enrollment->request_id }}</div>
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $enrollment->request_ip ?? 'Desconocido' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-extrabold ring-1 ring-inset {{ $statusClass }}">{{ $statusLabel }}</span>
                                        @if ($enrollment->asset)
                                            <a href="{{ route('assets.show', $enrollment->asset) }}" class="mt-2 block text-xs font-semibold text-blue-700 hover:text-blue-900">Activo #{{ $enrollment->asset_id }} · {{ $enrollment->asset->hostname }}</a>
                                        @else
                                            <div class="mt-2 text-xs text-slate-400">Sin activo vinculado</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($isActionable)
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <form method="POST" action="{{ route('admin.agent-enrollment-requests.approve', $enrollment) }}" data-sigma-confirm-form data-swal-icon="question" data-swal-title="¿Autorizar este equipo?" data-swal-text="Confirma que verificaste físicamente la identidad del equipo antes de autorizarlo." data-swal-confirm-text="Sí, autorizar">
                                                    @csrf
                                                    <button class="inline-flex h-9 items-center justify-center rounded-xl bg-blue-600 px-3 text-xs font-extrabold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">Aprobar</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.agent-enrollment-requests.recover', $enrollment) }}" data-sigma-confirm-form data-swal-icon="warning" data-swal-title="¿Recuperar el vínculo del agente?" data-swal-text="Verifica físicamente el equipo. SIGMA buscará el activo por UUID de hardware o serial único, cambiará el vínculo del agente y revocará las credenciales anteriores de ese activo." data-swal-confirm-text="Sí, recuperar activo" data-swal-cancel-text="Cancelar">
                                                    @csrf
                                                    <button class="inline-flex h-9 items-center justify-center rounded-xl border border-amber-300 bg-amber-50 px-3 text-xs font-extrabold text-amber-900 transition hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-200">Recuperar activo</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.agent-enrollment-requests.deny', $enrollment) }}" data-sigma-confirm-form data-swal-icon="warning" data-swal-title="¿Rechazar esta solicitud?" data-swal-text="El agente no quedará autorizado para enviar inventario." data-swal-confirm-text="Sí, rechazar" data-swal-cancel-text="Cancelar">
                                                    @csrf
                                                    <button class="inline-flex h-9 items-center justify-center rounded-xl border border-rose-200 bg-white px-3 text-xs font-extrabold text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-200">Rechazar</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-right text-xs text-slate-400">Sin acciones disponibles</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">✓</div>
                                        <p class="mt-3 font-bold text-slate-700">No hay solicitudes de enrolamiento</p>
                                        <p class="mt-1 text-sm text-slate-500">Las nuevas solicitudes de los agentes aparecerán aquí.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($requests->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">{{ $requests->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
