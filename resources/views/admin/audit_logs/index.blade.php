<x-app-layout>
    @php
        $actionLabels = [
            'viewed' => 'Consulta',
            'created' => 'Creación',
            'updated' => 'Actualización',
            'deleted' => 'Eliminación',
            'exported' => 'Exportación',
            'login' => 'Inicio de sesión',
            'logout' => 'Cierre de sesión',
            'action' => 'Acción',
        ];
    @endphp

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-[96%] max-w-[1600px] space-y-6">
            <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-violet-600"></div>
                <div class="flex items-center gap-4 pl-2">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-violet-100 bg-violet-50 text-violet-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.16em] text-violet-600">Administración</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Registro de Auditorías</h1>
                        <p class="mt-1 text-sm text-slate-500">Consulta las acciones realizadas en el sistema, quién las ejecutó y cuándo ocurrieron.</p>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Acciones de hoy</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">{{ number_format($summary['today']) }}</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Cambios de hoy</p>
                    <p class="mt-2 text-2xl font-black text-violet-700">{{ number_format($summary['changes']) }}</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Usuarios activos hoy</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">{{ number_format($summary['users']) }}</p>
                </article>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}"
                    x-data="auditLogFilters(@js(route('api.filters.audit-users')), @js(route('api.filters.audit-actions')), @js($filters['user_id']), @js($selectedUser?->user_name ?? ''), @js($selectedUser?->user_email ?? ''), @js($filters['action']), @js($actionLabels[$filters['action']] ?? ''))"
                    class="grid grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50/80 p-4 md:grid-cols-2 xl:grid-cols-6 xl:items-end">
                    <label class="block xl:col-span-2">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Buscar</span>
                        <input type="search" name="search" value="{{ $filters['search'] }}" maxlength="100" placeholder="Usuario, ruta o IP" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                    </label>
                    <div class="relative block" @click.away="userOpen = false">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Usuario</span>
                        <input type="hidden" name="user_id" :value="userId">
                        <input type="search" x-model="userSearch" @focus="userOpen = true; searchUsers()" @input="userId = ''; userOpen = true; searchUsers()" @keydown.escape="userOpen = false" autocomplete="off" placeholder="Todos los usuarios" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <div x-cloak x-show="userOpen" class="absolute z-30 mt-1 max-h-64 w-full overflow-auto rounded-xl border border-slate-200 bg-white p-1 shadow-xl">
                            <button type="button" @click="selectUser(null)" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-slate-600 hover:bg-violet-50">Todos los usuarios</button>
                            <template x-for="item in userResults" :key="item.id">
                                <button type="button" @click="selectUser(item)" class="block w-full rounded-lg px-3 py-2 text-left hover:bg-violet-50">
                                    <span class="block text-sm font-semibold text-slate-800" x-text="item.primary"></span>
                                    <span class="block text-xs text-slate-500" x-text="item.secondary"></span>
                                </button>
                            </template>
                            <p x-show="userLoading" class="px-3 py-2 text-xs text-slate-500">Buscando usuarios…</p>
                            <p x-show="!userLoading && userResults.length === 0" class="px-3 py-2 text-xs text-slate-500">No hay coincidencias.</p>
                        </div>
                    </div>
                    <div class="relative block" @click.away="actionOpen = false">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Acción</span>
                        <input type="hidden" name="action" :value="actionId">
                        <input type="search" x-model="actionSearch" @focus="actionOpen = true; searchActions()" @input="actionId = ''; actionOpen = true; searchActions()" @keydown.escape="actionOpen = false" autocomplete="off" placeholder="Todas las acciones" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                        <div x-cloak x-show="actionOpen" class="absolute z-30 mt-1 max-h-64 w-full overflow-auto rounded-xl border border-slate-200 bg-white p-1 shadow-xl">
                            <button type="button" @click="selectAction(null)" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-slate-600 hover:bg-violet-50">Todas las acciones</button>
                            <template x-for="item in actionResults" :key="item.id">
                                <button type="button" @click="selectAction(item)" class="block w-full rounded-lg px-3 py-2 text-left hover:bg-violet-50">
                                    <span class="block text-sm font-semibold text-slate-800" x-text="item.primary"></span>
                                    <span class="block text-xs text-slate-500" x-text="item.secondary"></span>
                                </button>
                            </template>
                            <p x-show="actionLoading" class="px-3 py-2 text-xs text-slate-500">Cargando acciones…</p>
                            <p x-show="!actionLoading && actionResults.length === 0" class="px-3 py-2 text-xs text-slate-500">No hay coincidencias.</p>
                        </div>
                    </div>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Desde</span>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Hasta</span>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                    </label>
                    <div class="flex gap-2 xl:col-span-6">
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-violet-600 px-4 text-sm font-bold text-white transition hover:bg-violet-700">Filtrar</button>
                        @if ($filters['search'] !== '' || $filters['action'] !== '' || $filters['date_from'] !== '' || $filters['date_to'] !== '' || $filters['user_id'] !== '')
                            <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-100">Limpiar</a>
                        @endif
                    </div>
                    @error('search')<p class="text-xs font-medium text-rose-600">La búsqueda no puede superar 100 caracteres.</p>@enderror
                    @error('date_to')<p class="text-xs font-medium text-rose-600">La fecha final debe ser igual o posterior a la inicial.</p>@enderror
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left">
                        <thead>
                            <tr class="border-b border-slate-200 bg-white text-[10px] font-extrabold uppercase tracking-widest text-slate-500">
                                <th class="px-5 py-4">Fecha</th>
                                <th class="px-5 py-4">Usuario</th>
                                <th class="px-5 py-4">Acción</th>
                                <th class="px-5 py-4">Recurso / ruta</th>
                                <th class="px-5 py-4">Resultado</th>
                                <th class="px-5 py-4">IP</th>
                                <th class="px-5 py-4">Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($logs as $log)
                                <tr class="align-top transition-colors hover:bg-violet-50/30">
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-slate-600">
                                        <div class="font-bold text-slate-800">{{ $log->created_at?->timezone(config('app.timezone'))->format('d/m/Y') }}</div>
                                        <div class="mt-1">{{ $log->created_at?->timezone(config('app.timezone'))->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800">{{ $log->user?->name ?? $log->user_name ?? 'Usuario eliminado' }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $log->user?->email ?? $log->user_email ?? 'Sin correo disponible' }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-extrabold {{ in_array($log->action, ['deleted'], true) ? 'bg-rose-50 text-rose-700' : (in_array($log->action, ['created', 'updated'], true) ? 'bg-violet-50 text-violet-700' : 'bg-slate-100 text-slate-600') }}">
                                            {{ $actionLabels[$log->action] ?? ucfirst($log->action) }}
                                        </span>
                                        <div class="mt-1.5 font-mono text-[10px] text-slate-400">{{ $log->method }}</div>
                                    </td>
                                    <td class="max-w-sm px-5 py-4">
                                        <div class="break-all font-semibold text-slate-700">{{ $log->route_name ?? $log->path }}</div>
                                        @if ($log->subject_type || $log->subject_id)
                                            <div class="mt-1 text-xs text-slate-500">{{ class_basename($log->subject_type ?? 'Registro') }}{{ $log->subject_id ? ' #'.$log->subject_id : '' }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-bold {{ ($log->status_code ?? 500) < 400 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $log->status_code ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $log->ip_address ?? '—' }}</td>
                                    <td class="px-5 py-4">
                                        @if ($log->details)
                                            <details class="max-w-xs">
                                                <summary class="cursor-pointer text-xs font-bold text-violet-700 hover:text-violet-900">Ver datos</summary>
                                                <pre class="mt-2 max-h-48 overflow-auto whitespace-pre-wrap break-all rounded-lg bg-slate-50 p-3 text-[10px] text-slate-600">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </details>
                                        @else
                                            <span class="text-xs text-slate-400">Sin datos adicionales</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center">
                                        <p class="font-bold text-slate-700">No hay acciones registradas para esos filtros.</p>
                                        <p class="mt-1 text-sm text-slate-500">Las nuevas acciones de los usuarios aparecerán aquí.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">{{ $logs->links() }}</div>
                @endif
            </section>
        </div>
    </div>

    <script>
        window.auditLogFilters = (usersUrl, actionsUrl, initialUserId, initialUserName, initialUserEmail, initialAction, initialActionLabel) => ({
            userId: initialUserId || '',
            userSearch: initialUserId ? (initialUserName || initialUserEmail || '') : '',
            userResults: [],
            userLoading: false,
            userOpen: false,
            userTimer: null,
            userRequest: 0,
            actionId: initialAction || '',
            actionSearch: initialActionLabel || '',
            actionResults: [],
            actionLoading: false,
            actionOpen: false,
            actionTimer: null,
            actionRequest: 0,

            searchUsers() {
                clearTimeout(this.userTimer);
                const requestId = ++this.userRequest;
                this.userTimer = setTimeout(async () => {
                    this.userLoading = true;
                    try {
                        const url = new URL(usersUrl, window.location.origin);
                        url.searchParams.set('q', this.userSearch.trim());
                        const response = await fetch(url, { headers: { Accept: 'application/json' } });
                        if (!response.ok) throw new Error('No se pudieron consultar los usuarios.');
                        const items = await response.json();
                        if (requestId === this.userRequest) this.userResults = items;
                    } catch (error) {
                        if (requestId === this.userRequest) this.userResults = [];
                    } finally {
                        if (requestId === this.userRequest) this.userLoading = false;
                    }
                }, 220);
            },

            selectUser(item) {
                this.userId = item?.id ?? '';
                this.userSearch = item?.primary ?? '';
                this.userOpen = false;
            },

            searchActions() {
                clearTimeout(this.actionTimer);
                const requestId = ++this.actionRequest;
                this.actionTimer = setTimeout(async () => {
                    this.actionLoading = true;
                    try {
                        const url = new URL(actionsUrl, window.location.origin);
                        url.searchParams.set('q', this.actionSearch.trim());
                        const response = await fetch(url, { headers: { Accept: 'application/json' } });
                        if (!response.ok) throw new Error('No se pudieron consultar las acciones.');
                        const items = await response.json();
                        if (requestId === this.actionRequest) this.actionResults = items;
                    } catch (error) {
                        if (requestId === this.actionRequest) this.actionResults = [];
                    } finally {
                        if (requestId === this.actionRequest) this.actionLoading = false;
                    }
                }, 180);
            },

            selectAction(item) {
                this.actionId = item?.id ?? '';
                this.actionSearch = item?.primary ?? '';
                this.actionOpen = false;
            },
        });
    </script>
</x-app-layout>
