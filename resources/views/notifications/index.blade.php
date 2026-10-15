<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-[96%] max-w-5xl space-y-6">
            <section class="relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-600"></div>
                <div class="flex items-center gap-4 pl-2">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4.5-5.8V4a1.5 1.5 0 00-3 0v1.2A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" /></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900">Notificaciones</h1>
                        <p class="mt-1 text-sm text-slate-500">Avisos de mantenimientos y actividad asignada a tu cuenta.</p>
                    </div>
                </div>
                @if(auth()->user()->unreadNotifications()->exists())
                    <form method="POST" action="{{ route('notifications.read-all') }}">@csrf @method('PATCH')
                        <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">Marcar todas como leídas</button>
                    </form>
                @endif
            </section>
            @if(session('success'))<div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>@endif
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @forelse($notifications as $notification)
                    @php($data = $notification->data ?? [])
                    <article class="flex flex-col gap-4 border-b border-slate-100 p-5 last:border-b-0 sm:flex-row sm:items-start sm:justify-between {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/50' }}">
                        <div class="flex gap-4">
                            <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $notification->read_at ? 'bg-slate-100 text-slate-500' : 'bg-blue-100 text-blue-700' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5m-8 5h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v13a2 2 0 002 2z" /></svg>
                            </span>
                            <div>
                                <div class="flex flex-wrap items-center gap-2"><h2 class="font-bold text-slate-900">{{ $data['title'] ?? 'Notificación' }}</h2>@unless($notification->read_at)<span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-blue-700">Nueva</span>@endunless</div>
                                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $data['message'] ?? 'Tienes una nueva notificación.' }}</p>
                                <p class="mt-2 text-xs text-slate-400">{{ $notification->created_at?->timezone(config('app.timezone'))->format('d/m/Y · g:i a') }}</p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-2 pl-14 sm:pl-0">
                            <a href="{{ $data['url'] ?? route('schedules.index') }}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-700">Ver cronograma</a>
                            @unless($notification->read_at)<form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf @method('PATCH')<button class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Marcar leída</button></form>@endunless
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-12 0v3.2A2 2 0 015.4 16L4 17h5m6 0a3 3 0 11-6 0" /></svg></div>
                        <h2 class="mt-4 font-bold text-slate-800">No tienes notificaciones</h2><p class="mt-1 text-sm text-slate-500">Aquí aparecerán los mantenimientos que te asignen.</p>
                    </div>
                @endforelse
            </section>
            @if($notifications->hasPages())<div class="rounded-xl border border-slate-200 bg-white px-4 py-3">{{ $notifications->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
