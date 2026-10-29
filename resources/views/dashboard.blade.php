<x-app-layout>
    <div class="min-h-full bg-slate-50 py-6 sm:py-8" x-data="{ activeTab: 'overview', dashboardReady: false, init() { window.setTimeout(() => this.dashboardReady = true, 80); } }">
        <div class="mx-auto w-full max-w-7xl space-y-6">
            <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-blue-600">SIGMA · Gestión tecnológica</p>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Centro de control</h1>
                    <p class="mt-1 text-sm text-slate-500">Resumen operativo del inventario y acceso a los informes institucionales.</p>
                </div>
                <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Conectividad calculada por heartbeat
                </div>
            </section>

            <nav class="flex w-fit max-w-full gap-1 overflow-x-auto rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm" role="tablist" aria-label="Secciones del dashboard">
                <button type="button" role="tab" id="dashboard-tab-overview" aria-controls="dashboard-panel-overview" :aria-selected="activeTab === 'overview'" @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'" class="inline-flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h8v8H3zM13 3h8v5h-8zM13 10h8v11h-8zM3 13h8v8H3z" /></svg>
                    Resumen SIGMA
                </button>
                <button type="button" role="tab" id="dashboard-tab-powerbi" aria-controls="dashboard-panel-powerbi" :aria-selected="activeTab === 'powerbi'" @click="activeTab = 'powerbi'" :class="activeTab === 'powerbi' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'" class="inline-flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h17M8 15v-3m5 3V7m5 8V9" /></svg>
                    Informes Power BI
                </button>
            </nav>

            <section x-cloak x-show="activeTab === 'overview'" x-transition.opacity role="tabpanel" id="dashboard-panel-overview" aria-labelledby="dashboard-tab-overview" class="space-y-4 transition-all duration-500 ease-out motion-reduce:transition-none" :class="dashboardReady ? 'translate-y-0 opacity-100' : 'translate-y-2 opacity-0'">
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1.65fr)_minmax(19rem,0.85fr)]">
                    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5" aria-labelledby="assets-by-campus-title">
                        <header class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h2 id="assets-by-campus-title" class="text-sm font-extrabold text-slate-800">Equipos distribuidos por sede</h2>
                                <p class="mt-1 text-xs text-slate-500">Cantidad de activos ubicados en cada sede según su espacio asignado.</p>
                            </div>
                            <span class="w-fit rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold text-blue-700">{{ number_format($campusCount) }} sedes</span>
                        </header>

                        <div class="grid grid-cols-1 gap-x-8 gap-y-3 sm:grid-cols-2">
                            @forelse($assetsByCampus as $campusAssets)
                                @php
                                    $campusAssetCount = (int) $campusAssets->total;
                                    $campusBarWidth = $campusAssetCount > 0 ? max(2, ($campusAssetCount / $maxAssetsByCampus) * 100) : 0;
                                    $isUnassignedCampus = $campusAssets->id === null;
                                @endphp
                                <div class="min-w-0">
                                    <div class="mb-1.5 flex items-baseline justify-between gap-3">
                                        <span class="truncate text-xs font-semibold {{ $isUnassignedCampus ? 'text-amber-700' : 'text-slate-700' }}">{{ $campusAssets->name }}</span>
                                        <span class="shrink-0 text-xs font-extrabold text-slate-800">{{ number_format($campusAssetCount) }}</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-label="{{ $campusAssets->name }}: {{ $campusAssetCount }} equipos" aria-valuemin="0" aria-valuemax="{{ $maxAssetsByCampus }}" aria-valuenow="{{ $campusAssetCount }}">
                                        <div class="h-full rounded-full {{ $isUnassignedCampus ? 'bg-amber-400' : 'bg-blue-600' }} transition-all duration-700 ease-out motion-reduce:transition-none" :style="'width: ' + (dashboardReady ? '{{ $campusBarWidth }}' : '0') + '%' "></div>
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-full py-6 text-center text-sm text-slate-500">No hay sedes registradas para comparar.</p>
                            @endforelse
                        </div>
                        <p class="mt-4 border-t border-slate-100 pt-3 text-[11px] text-slate-400">Los activos sin una sede relacionada se muestran aparte.</p>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5" aria-labelledby="technician-reports-title">
                        <header class="mb-3">
                            <h2 id="technician-reports-title" class="text-sm font-extrabold text-slate-800">Reportes técnicos por técnico</h2>
                            <p class="mt-1 text-xs text-slate-500">Participación en los reportes registrados.</p>
                        </header>

                        @if($technicianReportTotal > 0)
                            <div class="mb-3 flex items-baseline justify-between rounded-xl bg-slate-50 px-3 py-2">
                                <span class="text-xs font-semibold text-slate-500">Total de reportes</span>
                                <span class="text-base font-extrabold text-slate-800">{{ number_format($technicianReportTotal) }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="h-28 w-28 shrink-0 rounded-full ring-4 ring-white shadow-md" style="background: {{ $technicianPieStyle }}" role="img" aria-label="Gráfico de torta con la distribución de reportes técnicos"></div>
                                <ul class="max-h-36 min-w-0 flex-1 space-y-2 overflow-y-auto pr-1">
                                    @foreach($technicianReportSegments as $segment)
                                        <li class="flex items-start gap-2 text-xs">
                                            <span class="mt-0.5 h-2.5 w-2.5 shrink-0 rounded-sm" style="background-color: {{ $segment->color }}"></span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block truncate font-semibold text-slate-700">{{ $segment->technician_name }}</span>
                                                <span class="text-[10px] text-slate-500">{{ number_format($segment->total) }} · {{ number_format($segment->percentage, 1) }}%</span>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="flex h-40 flex-col items-center justify-center rounded-xl bg-slate-50 px-4 text-center">
                                <span class="h-20 w-20 rounded-full border-[12px] border-slate-200"></span>
                                <p class="mt-3 text-sm font-bold text-slate-700">Sin reportes técnicos todavía</p>
                                <p class="mt-1 text-xs text-slate-500">La distribución aparecerá cuando se registren reportes.</p>
                            </div>
                        @endif
                    </section>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-2">
                            <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Activos registrados</p><p class="mt-1.5 text-2xl font-extrabold tracking-tight text-slate-900">{{ number_format($totalAssets) }}</p></div>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2h-4l-2 2-2-2H6a2 2 0 01-2-2V5z" /></svg></span>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-500">Equipos en inventario</p>
                    </article>
                    <article class="rounded-xl border border-emerald-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-2">
                            <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Agentes en línea</p><p class="mt-1.5 text-2xl font-extrabold tracking-tight text-emerald-600">{{ number_format($onlineAgents) }}</p></div>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12l4 4L19 6" /></svg></span>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-500">Heartbeat en {{ \App\Models\Asset::ONLINE_THRESHOLD_MINUTES }} min</p>
                    </article>
                    <article class="rounded-xl border border-amber-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-2">
                            <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Agentes desconectados</p><p class="mt-1.5 text-2xl font-extrabold tracking-tight text-amber-600">{{ number_format($offlineAgents) }}</p></div>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 001.7 3h16.9a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" /></svg></span>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-500">Sin heartbeat reciente</p>
                    </article>
                    <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-2">
                            <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Cobertura del agente</p><p class="mt-1.5 text-2xl font-extrabold tracking-tight text-slate-900">{{ $totalAssets > 0 ? number_format(($managedAgents / $totalAssets) * 100, 1) : '0.0' }}<span class="text-base">%</span></p></div>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h17M8 15v-3m5 3V7m5 8V9" /></svg></span>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-500">{{ number_format($managedAgents) }} de {{ number_format($totalAssets) }} activos</p>
                    </article>
                </div>

                <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1.6fr)_minmax(18rem,0.8fr)]">
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <header class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-sm font-extrabold text-slate-800">Equipos reportados recientemente</h2>
                                <p class="mt-1 text-xs text-slate-500">Últimos activos con datos recibidos por SIGMA.</p>
                            </div>
                            <a href="{{ route('assets.index') }}" class="inline-flex w-fit items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                Ir al inventario <span aria-hidden="true">→</span>
                            </a>
                        </header>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentAssets as $asset)
                                @php
                                    $lastHeartbeat = $asset->agent_last_seen;
                                    $assetIsOnline = $asset->is_agent_managed && $lastHeartbeat && $lastHeartbeat->gte(now()->subMinutes(\App\Models\Asset::ONLINE_THRESHOLD_MINUTES));
                                @endphp
                                <a href="{{ route('assets.show', $asset) }}" class="flex items-center justify-between gap-4 px-5 py-3.5 transition hover:bg-slate-50">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5h16v11H4zM8 20h8m-4-4v4" /></svg></span>
                                        <span class="min-w-0">
                                            <span class="block truncate text-sm font-bold text-slate-800">{{ $asset->hostname ?: ($asset->internal_code ?: $asset->serial_number) }}</span>
                                            <span class="mt-0.5 block truncate text-xs text-slate-500">{{ $asset->model_version ?: 'Modelo sin registrar' }} · {{ $asset->internal_code ?: $asset->serial_number }}</span>
                                        </span>
                                    </div>
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide {{ $assetIsOnline ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $assetIsOnline ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $assetIsOnline ? 'En línea' : 'Sin conexión' }}
                                    </span>
                                </a>
                            @empty
                                <div class="px-5 py-12 text-center">
                                    <p class="text-sm font-bold text-slate-700">Todavía no hay activos registrados</p>
                                    <p class="mt-1 text-xs text-slate-500">Cuando el inventario reciba equipos, aparecerán aquí.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <aside class="space-y-5">
                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="text-sm font-extrabold text-slate-800">Resumen de infraestructura</h2>
                            <p class="mt-1 text-xs text-slate-500">Ubicaciones disponibles en SIGMA.</p>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-100">
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Sedes</p>
                                    <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ number_format($campusCount) }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-100">
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">Espacios</p>
                                    <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ number_format($roomCount) }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-5 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-blue-600">Modelo frecuente</p>
                            <p class="mt-2 truncate text-lg font-extrabold text-slate-800">{{ $mostUsedModel->model_version ?? 'Sin datos disponibles' }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $mostUsedModel->total ?? 0 }} equipos registrados con este modelo.</p>
                        </section>

                    </aside>
                </div>
            </section>

            <section x-cloak x-show="activeTab === 'powerbi'" x-transition.opacity role="tabpanel" id="dashboard-panel-powerbi" aria-labelledby="dashboard-tab-powerbi" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-800">Informes Power BI</h2>
                        <p class="mt-1 text-xs text-slate-500">Reportes institucionales en una vista independiente del resumen operativo.</p>
                    </div>
                    @if($powerBiEmbedUrl)
                        <a href="{{ $powerBiEmbedUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                            Abrir en Power BI <span aria-hidden="true">↗</span>
                        </a>
                    @endif
                </header>
                @if($powerBiEmbedUrl)
                    <div class="min-h-[65vh] bg-slate-100 p-2 sm:p-4">
                        <iframe src="{{ $powerBiEmbedUrl }}" title="Informes institucionales de Power BI" class="h-[65vh] min-h-[32rem] w-full rounded-xl border border-slate-200 bg-white" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </div>
                @else
                    <div class="flex min-h-[22rem] flex-col items-center justify-center px-6 py-12 text-center">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h17M8 15v-3m5 3V7m5 8V9" /></svg>
                        </span>
                        <h3 class="mt-4 text-base font-extrabold text-slate-800">Power BI está listo para conectarse</h3>
                        <p class="mt-2 max-w-lg text-sm leading-6 text-slate-500">Configura la URL segura de inserción del informe en <span class="font-semibold text-slate-700">POWER_BI_EMBED_URL</span> para mostrarlo en esta pestaña.</p>
                        <p class="mt-3 text-xs text-slate-400">Usa inserción autenticada institucional. No publiques informes con datos internos en la web.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
