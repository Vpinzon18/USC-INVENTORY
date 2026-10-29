<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-[96%] max-w-[1500px] space-y-6">
            <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-600">Administración · Cronograma</p>
                    <h1 class="mt-1 text-2xl font-black text-slate-900">Solicitudes de ampliación</h1>
                    <p class="mt-1 text-sm text-slate-500">Revisa la justificación y define el nuevo plazo en SIGMA.</p>
                </div>
                <a href="{{ route('schedules.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 px-4 text-sm font-bold text-slate-700 hover:bg-slate-50">Volver al cronograma</a>
            </section>

            @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-700">{{ $pendingCount }} solicitud{{ $pendingCount === 1 ? '' : 'es' }} pendiente{{ $pendingCount === 1 ? '' : 's' }}</p>
                    <form method="GET" class="flex items-center gap-2">
                        <label for="extension-status" class="sr-only">Filtrar solicitudes</label>
                        <select id="extension-status" name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 bg-white text-sm">
                            <option value="">Todas</option>
                            @foreach(['PENDIENTE' => 'Pendientes', 'APROBADA' => 'Aprobadas', 'RECHAZADA' => 'Rechazadas'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($requests as $extension)
                        @php($schedule = $extension->schedule)
                        <article class="p-5 sm:p-6">
                            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="font-extrabold text-slate-900">{{ $schedule?->asset?->serial_number ?? 'Agenda eliminada' }}</h2>
                                        <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-wide {{ $extension->status === 'PENDIENTE' ? 'bg-amber-100 text-amber-800' : ($extension->status === 'APROBADA' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800') }}">{{ $extension->status }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">{{ $schedule?->asset?->internal_code ?? 'Sin placa' }} · {{ $schedule?->asset?->room?->building?->name ?? 'Ubicación no disponible' }} · Técnico: {{ $extension->technician?->name ?? 'No disponible' }}</p>
                                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                        <div class="rounded-lg bg-slate-50 p-3"><p class="text-[10px] font-bold uppercase text-slate-500">Límite original</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $extension->original_deadline_at?->format('d/m/Y H:i') ?? '—' }}</p></div>
                                        <div class="rounded-lg bg-blue-50 p-3"><p class="text-[10px] font-bold uppercase text-blue-600">Nueva fecha solicitada</p><p class="mt-1 text-sm font-semibold text-blue-900">{{ $extension->requested_deadline_at?->format('d/m/Y H:i') }}</p></div>
                                        <div class="rounded-lg bg-slate-50 p-3"><p class="text-[10px] font-bold uppercase text-slate-500">Enviada</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $extension->created_at?->format('d/m/Y H:i') }}</p></div>
                                    </div>
                                    <div class="mt-4 rounded-xl border border-slate-200 p-4"><p class="text-[10px] font-black uppercase tracking-wide text-slate-500">Justificación del técnico</p><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $extension->justification }}</p></div>
                                    @if($extension->status !== 'PENDIENTE')
                                        <p class="mt-3 text-xs text-slate-500">{{ $extension->status === 'APROBADA' ? 'Nuevo límite: '.$extension->approved_deadline_at?->format('d/m/Y H:i') : 'Rechazada' }} · Decidió: {{ $extension->decider?->name ?? 'Administrador' }}{{ $extension->decision_note ? ' · '.$extension->decision_note : '' }}</p>
                                    @endif
                                </div>

                                @if($extension->status === 'PENDIENTE' && $schedule)
                                    <div class="w-full rounded-xl border border-slate-200 bg-slate-50 p-4 xl:max-w-sm">
                                        <form method="POST" action="{{ route('admin.schedules.extensions.approve', $extension) }}" class="space-y-3">
                                            @csrf
                                            <label class="block"><span class="mb-1 block text-[10px] font-extrabold uppercase tracking-wide text-slate-600">Fecha aprobada</span><input type="datetime-local" name="approved_deadline_at" required min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ $extension->requested_deadline_at?->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border-slate-300 text-sm"></label>
                                            <label class="block"><span class="mb-1 block text-[10px] font-extrabold uppercase tracking-wide text-slate-600">Observación (opcional)</span><textarea name="decision_note" rows="2" maxlength="2000" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Nota para el historial"></textarea></label>
                                            <button class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Aprobar y actualizar plazo</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.schedules.extensions.reject', $extension) }}" class="mt-4 space-y-2 border-t border-slate-200 pt-4">
                                            @csrf
                                            <label class="block"><span class="mb-1 block text-[10px] font-extrabold uppercase tracking-wide text-slate-600">Motivo del rechazo</span><textarea name="decision_note" rows="2" required minlength="5" maxlength="2000" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Obligatorio si rechazas"></textarea></label>
                                            <button class="w-full rounded-lg border border-rose-200 bg-white px-4 py-2.5 text-sm font-bold text-rose-700 hover:bg-rose-50">Rechazar solicitud</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-16 text-center text-sm text-slate-500">No hay solicitudes de ampliación para este filtro.</div>
                    @endforelse
                </div>
                <div class="border-t border-slate-200 p-4">{{ $requests->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
