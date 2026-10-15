<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8" x-data="maintenanceGantt(@js($maintenanceRows), @js($calendarMonth))">
        <div class="mx-auto w-[96%] max-w-[1600px] space-y-6">
            <section class="relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-600"></div>
                <div class="flex items-center gap-4 pl-2">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Zm3 10h3m-3 4h7"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-600">Mantenimiento</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Cronograma de mantenimientos</h1>
                        <p class="mt-1 text-sm text-slate-500">Planificación, ejecución y ubicación de los equipos por técnico.</p>
                    </div>
                </div>
                <a href="{{ route('schedules.index') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                    Ver listado
                </a>
            </section>

            <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <template x-for="card in summary" :key="card.label">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500" x-text="card.label"></p>
                        <p class="mt-2 text-2xl font-black text-slate-900" x-text="card.value"></p>
                    </div>
                </template>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50/80 p-4 lg:flex-row lg:items-end lg:justify-between lg:p-5">
                    <div class="grid flex-1 grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Buscar equipo</span>
                            <input x-model.debounce.200ms="search" type="search" placeholder="Nombre, serial, placa o custodiante" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Técnico asignado</span>
                            <select x-model="technicianFilter" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los técnicos</option>
                                <template x-for="name in technicians" :key="name"><option :value="name" x-text="name"></option></template>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Sede</span>
                            <select x-model="campusFilter" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas las sedes</option>
                                <template x-for="name in campuses" :key="name"><option :value="name" x-text="name"></option></template>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Estado</span>
                            <select x-model="statusFilter" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="PENDIENTE">Pendiente</option>
                                <option value="VENCIDO">Vencido</option>
                                <option value="REALIZADO">Realizado</option>
                            </select>
                        </label>
                    </div>
                    <button @click="search = ''; technicianFilter = ''; campusFilter = ''; statusFilter = ''" type="button" class="h-10 shrink-0 rounded-xl px-4 text-sm font-bold text-slate-500 transition hover:bg-white hover:text-blue-700">Limpiar filtros</button>
                </div>

                <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-extrabold text-slate-900">Diagrama de Gantt</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Cada barra indica la fecha programada; el punto verde señala la fecha de ejecución registrada.</p>
                    </div>
                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                        <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 p-1">
                            <button @click="moveMonth(-1)" type="button" aria-label="Mes anterior" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 hover:bg-white hover:shadow-sm">‹</button>
                            <button @click="goToday()" type="button" class="min-w-32 px-2 text-center text-sm font-extrabold capitalize text-slate-800" x-text="monthLabel"></button>
                            <button @click="moveMonth(1)" type="button" aria-label="Mes siguiente" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 hover:bg-white hover:shadow-sm">›</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="min-w-max">
                        <div class="grid border-b border-slate-200 bg-slate-50" style="grid-template-columns: 360px repeat(42, 42px)">
                            <div class="sticky left-0 z-20 flex items-center border-r border-slate-200 bg-slate-50 px-4 py-3 text-[11px] font-black uppercase tracking-wider text-slate-500">Técnico / equipo</div>
                            <template x-for="day in days" :key="day.key">
                                <div class="flex flex-col items-center justify-center border-r border-slate-100 py-2" :class="day.isToday ? 'bg-blue-50' : (day.inMonth ? '' : 'bg-slate-100/70')">
                                    <span class="text-[9px] font-bold uppercase text-slate-400" x-text="day.weekday"></span>
                                    <span class="mt-0.5 text-xs font-extrabold" :class="day.isToday ? 'text-blue-700' : (day.inMonth ? 'text-slate-700' : 'text-slate-400')" x-text="day.number"></span>
                                </div>
                            </template>
                        </div>

                        <template x-for="group in groups" :key="group.name">
                            <div>
                                <div class="sticky left-0 z-10 flex items-center gap-2 border-b border-slate-200 bg-blue-50/90 px-4 py-2.5 text-sm font-extrabold text-slate-800">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-[10px] font-black text-blue-700" x-text="initials(group.name)"></span>
                                    <span x-text="group.name"></span>
                                    <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-bold text-slate-500" x-text="group.items.length + (group.items.length === 1 ? ' mantenimiento' : ' mantenimientos')"></span>
                                </div>
                                <template x-for="item in group.items" :key="item.id">
                                    <div class="grid min-h-[74px] border-b border-slate-100 hover:bg-slate-50/70" style="grid-template-columns: 360px repeat(42, 42px)">
                                        <div class="sticky left-0 z-[1] flex min-w-0 flex-col justify-center border-r border-slate-200 bg-white px-4 py-2">
                                            <div class="flex min-w-0 items-center gap-2">
                                                <span class="truncate text-xs font-extrabold text-slate-900" x-text="item.assetName"></span>
                                                <span class="shrink-0 rounded-md px-2 py-0.5 text-[9px] font-black" :class="statusClass(item.status)" x-text="statusLabel(item.status)"></span>
                                            </div>
                                            <div class="mt-1 truncate text-[10px] text-slate-500"><span x-text="item.serial"></span><span class="mx-1">·</span><span x-text="item.plate"></span></div>
                                            <div class="mt-1 truncate text-[10px] text-slate-500" :title="item.location" x-text="item.location"></div>
                                            <div class="truncate text-[10px] text-slate-400">Custodiante: <span class="font-semibold text-slate-600" x-text="item.custodian"></span></div>
                                            <div class="truncate text-[10px] text-slate-400">Realizó: <span class="font-semibold text-slate-600" x-text="item.performer || 'Pendiente de ejecución'"></span></div>
                                        </div>
                                        <template x-for="day in days" :key="day.key">
                                            <div class="relative border-r border-slate-100" :class="day.isToday ? 'bg-blue-50/50' : (day.inMonth ? '' : 'bg-slate-50/60')">
                                                <template x-if="item.scheduledDate === day.key">
                                                    <a :href="item.executionUrl || '#detalle-' + item.id" :title="'Programado: ' + formatDate(item.scheduledDate) + (item.executionUrl ? ' · Abrir ejecución' : '')" class="absolute left-1 top-1/2 z-[2] flex h-7 w-10 -translate-y-1/2 items-center justify-center rounded-lg shadow-sm transition hover:scale-105" :class="barClass(item.status)">
                                                        <span class="text-[9px] font-black" x-text="day.number"></span>
                                                    </a>
                                                </template>
                                                <template x-if="item.performedDate === day.key">
                                                    <span :title="'Realizado: ' + formatDate(item.performedDate) + ' · ' + (item.performer || '')" class="absolute left-7 top-1/2 z-[3] h-3.5 w-3.5 -translate-y-1/2 rounded-full border-2 border-white bg-emerald-500 shadow ring-1 ring-emerald-300"></span>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div x-show="groups.length === 0" class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5M5 3h14a2 2 0 0 1 2 2v14H3V5a2 2 0 0 1 2-2Z"/></svg>
                            </div>
                            <p class="mt-3 font-bold text-slate-700">No hay agendas para mostrar</p>
                            <p class="mt-1 text-sm text-slate-500">Cambia los filtros o programa un mantenimiento nuevo.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                    <span class="font-bold uppercase tracking-wide text-slate-500">Leyenda</span>
                    <span class="inline-flex items-center gap-2"><i class="h-3 w-5 rounded bg-amber-400"></i> Pendiente</span>
                    <span class="inline-flex items-center gap-2"><i class="h-3 w-5 rounded bg-rose-500"></i> Vencido</span>
                    <span class="inline-flex items-center gap-2"><i class="h-3 w-5 rounded bg-emerald-500"></i> Realizado</span>
                    <span class="inline-flex items-center gap-2"><i class="h-3 w-3 rounded-full bg-emerald-500 ring-2 ring-emerald-200"></i> Fecha real de ejecución</span>
                </div>
            </section>
        </div>
    </div>

    <script>
        window.maintenanceGantt = (items, initialMonth) => ({
            items,
            search: '',
            technicianFilter: '',
            campusFilter: '',
            statusFilter: '',
            monthCursor: (() => { const [year, month] = initialMonth.split('-').map(Number); return new Date(year, month - 1, 1); })(),
            get technicians() { return [...new Set(this.items.map(item => item.technician))].sort((a, b) => a.localeCompare(b)); },
            get campuses() { return [...new Set(this.items.map(item => item.campus))].sort((a, b) => a.localeCompare(b)); },
            get visibleItems() {
                const term = this.search.trim().toLocaleLowerCase('es');
                return this.items.filter(item => {
                    const matchesTerm = !term || [item.assetName, item.serial, item.plate, item.custodian, item.location, item.performer, item.technician].some(value => (value || '').toLocaleLowerCase('es').includes(term));
                    return matchesTerm && (!this.technicianFilter || item.technician === this.technicianFilter) && (!this.campusFilter || item.campus === this.campusFilter) && (!this.statusFilter || item.status === this.statusFilter);
                });
            },
            get groups() {
                const grouped = this.visibleItems.reduce((result, item) => { (result[item.technician] ||= []).push(item); return result; }, {});
                return Object.entries(grouped).sort(([a], [b]) => a.localeCompare(b)).map(([name, groupItems]) => ({ name, items: groupItems }));
            },
            get summary() {
                const data = this.visibleItems;
                return [
                    { label: 'Agendas visibles', value: data.length },
                    { label: 'Técnicos', value: new Set(data.map(item => item.technician)).size },
                    { label: 'Pendientes / vencidos', value: data.filter(item => item.status === 'PENDIENTE' || item.status === 'VENCIDO').length },
                    { label: 'Mantenimientos realizados', value: data.filter(item => item.performedDate || item.status === 'REALIZADO').length },
                ];
            },
            get monthLabel() { return this.monthCursor.toLocaleDateString('es-CO', { month: 'long', year: 'numeric' }); },
            get days() {
                const first = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth(), 1);
                const mondayOffset = (first.getDay() + 6) % 7;
                const start = new Date(first); start.setDate(first.getDate() - mondayOffset);
                const today = this.keyOf(new Date());
                return Array.from({ length: 42 }, (_, index) => {
                    const date = new Date(start); date.setDate(start.getDate() + index);
                    return { key: this.keyOf(date), number: date.getDate(), weekday: date.toLocaleDateString('es-CO', { weekday: 'short' }).replace('.', ''), inMonth: date.getMonth() === this.monthCursor.getMonth(), isToday: this.keyOf(date) === today };
                });
            },
            keyOf(date) { return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`; },
            moveMonth(amount) { this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() + amount, 1); },
            goToday() { this.monthCursor = new Date(new Date().getFullYear(), new Date().getMonth(), 1); },
            initials(name) { return (name || '?').split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase(); },
            formatDate(value) { return value ? new Date(`${value}T12:00:00`).toLocaleDateString('es-CO', { day: '2-digit', month: 'short', year: 'numeric' }) : 'Sin fecha'; },
            statusLabel(status) { return ({ PENDIENTE: 'PENDIENTE', VENCIDO: 'VENCIDO', REALIZADO: 'REALIZADO' })[status] || status; },
            statusClass(status) { return ({ PENDIENTE: 'bg-amber-50 text-amber-700', VENCIDO: 'bg-rose-50 text-rose-700', REALIZADO: 'bg-emerald-50 text-emerald-700' })[status] || 'bg-slate-100 text-slate-600'; },
            barClass(status) { return ({ PENDIENTE: 'bg-amber-300 text-amber-950 hover:bg-amber-400', VENCIDO: 'bg-rose-500 text-white hover:bg-rose-600', REALIZADO: 'bg-emerald-500 text-white hover:bg-emerald-600' })[status] || 'bg-blue-500 text-white'; },
        });
    </script>
</x-app-layout>
