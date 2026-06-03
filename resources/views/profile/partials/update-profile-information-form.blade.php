<section>
    <header>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Actualiza la información de tu cuenta y tu dirección de correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="relative">
            <x-input-label for="name" :value="__('Nombre Completo')" class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-1.5" />
            <x-text-input id="name" name="name" type="text" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all text-sm py-2.5 px-4" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-500 text-xs font-medium" :messages="$errors->get('name')" />
        </div>

        <div class="relative">
            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-1.5" />
            <x-text-input id="email" name="email" type="email" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all text-sm py-2.5 px-4" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-500 text-xs font-medium" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50/80 border border-amber-200 rounded-xl shadow-sm flex flex-col gap-2">
                    <div class="flex items-center gap-2 text-amber-800">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="text-sm font-bold">Tu correo no está verificado.</span>
                    </div>
                    
                    <button form="send-verification" class="self-start text-[10px] font-extrabold text-amber-600 uppercase tracking-widest hover:text-amber-800 transition-colors focus:outline-none mt-1">
                        Reenviar enlace de verificación
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-emerald-600 flex items-center gap-1.5 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Se ha enviado un nuevo enlace de verificación.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all shadow-md shadow-blue-500/20">
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Guardado
                </p>
            @endif
        </div>
    </form>
</section>