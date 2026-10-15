<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b2e59">
    <title>SIGMA LOGIN | USC</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logoUSC.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes sigma-brand-enter {
            from { opacity: 0; transform: translate3d(0, 18px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        @keyframes sigma-brand-drift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -10px, 0) scale(1.025); }
        }
        @keyframes sigma-accent-reveal {
            from { opacity: .35; transform: scaleX(.15); transform-origin: left; }
            to { opacity: 1; transform: scaleX(1); transform-origin: left; }
        }
        .sigma-brand-enter { animation: sigma-brand-enter .75s cubic-bezier(.2,.7,.2,1) both; }
        .sigma-brand-enter-delay { animation: sigma-brand-enter .75s cubic-bezier(.2,.7,.2,1) .16s both; }
        .sigma-brand-enter-late { animation: sigma-brand-enter .75s cubic-bezier(.2,.7,.2,1) .3s both; }
        .sigma-brand-drift { animation: sigma-brand-drift 12s ease-in-out infinite; }
        .sigma-brand-accent { animation: sigma-accent-reveal .9s ease-out .45s both; }
        @media (prefers-reduced-motion: reduce) {
            .sigma-brand-enter, .sigma-brand-enter-delay, .sigma-brand-enter-late,
            .sigma-brand-drift, .sigma-brand-accent {
                animation: none !important;
                transform: none !important;
                opacity: 1 !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-900">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="relative isolate hidden min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-20">
            <div class="sigma-brand-drift absolute -right-32 -top-32 -z-10 h-96 w-96 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="sigma-brand-drift absolute -bottom-56 -left-32 -z-10 h-[34rem] w-[34rem] rounded-full border border-white/10" aria-hidden="true" style="animation-delay:-6s"></div>
            <div class="absolute right-20 top-1/3 -z-10 h-40 w-40 rounded-full bg-red-500/10 blur-3xl" aria-hidden="true"></div>

            <a href="{{ route('login') }}" class="sigma-brand-enter inline-flex w-fit items-center gap-5" aria-label="SIGMA, Universidad Santiago de Cali">
                <span class="rounded-3xl bg-white p-3 shadow-2xl shadow-black/30 ring-1 ring-white/60">
                    <img src="{{ asset('img/logoUSC.png') }}" alt="Logo institucional de la Universidad Santiago de Cali" class="h-20 w-20 object-contain xl:h-24 xl:w-24">
                </span>
                <span class="block border-l border-white/20 pl-5">
                    <span class="block text-3xl font-black tracking-tight">SIGMA</span>
                    <span class="mt-1 block text-[10px] font-bold uppercase tracking-[0.24em] text-slate-300">Gestión tecnológica</span>
                    <span class="mt-3 block text-[10px] font-semibold uppercase tracking-[0.16em] text-red-300">Universidad Santiago de Cali</span>
                </span>
            </a>

            <div class="sigma-brand-enter-delay relative z-10 max-w-xl py-14">
                
                <h1 class="text-4xl font-black leading-tight tracking-tight xl:text-5xl">Gestión tecnológica</h1>
                <p class="mt-6 max-w-lg text-base leading-7 text-slate-300">Sistema de Informacion Gestión y Manejo de Activos Tecnologicos</p>
                <div class="sigma-brand-accent mt-8 h-1 w-20 rounded-full bg-red-600"></div>
            </div>

            <p class="sigma-brand-enter-late text-xs font-medium tracking-wide text-slate-400">Universidad Santiago de Cali <span class="mx-2 text-white/30">•</span> SIGMA</p>
        </section>

        <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
            <div class="w-full max-w-md">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <span class="rounded-xl bg-white p-2 shadow-sm ring-1 ring-slate-200">
                        <img src="{{ asset('img/logoUSC.png') }}" alt="Universidad Santiago de Cali" class="h-11 w-11 object-contain">
                    </span>
                    <span>
                        <span class="block text-lg font-black tracking-tight text-slate-900">SIGMA</span>
                        <span class="block text-[10px] font-bold uppercase tracking-[0.18em] text-red-700">Gestión tecnológica USC</span>
                    </span>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-200/70 sm:p-10">
                    <div class="mb-8">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-red-700">Acceso institucional</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900">SIGMA LOGIN</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Ingresa tus credenciales para continuar.</p>
                    </div>

                    @if (session('status'))
                        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('status') }}</div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Correo institucional</label>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6.75h16.5v10.5H3.75zM4.5 7.5l7.5 6 7.5-6"/></svg>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" inputmode="email" placeholder="nombre@usc.edu.co" aria-describedby="email-error" class="block h-12 w-full rounded-xl border border-slate-300 bg-slate-50 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-red-700 focus:bg-white focus:ring-4 focus:ring-red-100">
                            </div>
                            <x-input-error id="email-error" :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="password" class="block text-sm font-semibold text-slate-700">Contraseña</label>
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-red-700 transition hover:text-red-900 hover:underline">¿Olvidaste tu contraseña?</a>
                            </div>
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 10V7a4.5 4.5 0 0 1 9 0v3m-10.5 0h12A1.5 1.5 0 0 1 19.5 11.5v8A1.5 1.5 0 0 1 18 21H6a1.5 1.5 0 0 1-1.5-1.5v-8A1.5 1.5 0 0 1 6 10.5Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14v3"/></svg>
                                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Escribe tu contraseña" aria-describedby="password-error" class="block h-12 w-full rounded-xl border border-slate-300 bg-slate-50 pl-11 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-red-700 focus:bg-white focus:ring-4 focus:ring-red-100">
                                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center rounded-r-xl text-slate-400 transition hover:text-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-red-600" aria-label="Mostrar contraseña" aria-pressed="false" aria-controls="password">
                                    <svg id="icon-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6Z"/><circle cx="12" cy="12" r="2.75" stroke-width="1.8"/></svg>
                                    <svg id="icon-eye-off" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 6.2A10.8 10.8 0 0 1 12 6c6.25 0 9.75 6 9.75 6a16.3 16.3 0 0 1-3.1 3.55M6.2 6.2C3.65 7.85 2.25 12 2.25 12s3.5 6 9.75 6c1.22 0 2.32-.22 3.3-.58"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.88 9.88a3 3 0 0 0 4.24 4.24"/></svg>
                                </button>
                            </div>
                            <x-input-error id="password-error" :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
                        </div>

                        <label for="remember" class="flex w-fit cursor-pointer items-center gap-2.5 text-sm text-slate-600">
                            <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-red-700 focus:ring-2 focus:ring-red-200">
                            <span>Recordarme en este equipo</span>
                        </label>

                        <button type="submit" class="group inline-flex h-12 w-full items-center justify-center gap-2.5 rounded-xl bg-red-700 px-5 text-sm font-bold text-white shadow-lg shadow-red-900/15 transition hover:-translate-y-0.5 hover:bg-red-800 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-red-200 active:translate-y-0">
                            Ingresar a SIGMA
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </button>
                    </form>
                </div>

                <p class="mt-6 text-center text-xs leading-5 text-slate-500">© {{ date('Y') }} Universidad Santiago de Cali<br><span class="font-medium text-slate-400">Sistema de Gestión Tecnológica</span></p>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const password = document.getElementById('password');
            const toggle = document.getElementById('toggle-password');
            const eye = document.getElementById('icon-eye');
            const eyeOff = document.getElementById('icon-eye-off');

            if (!password || !toggle || !eye || !eyeOff) return;

            toggle.addEventListener('click', () => {
                const showing = password.type === 'password';
                password.type = showing ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', String(showing));
                toggle.setAttribute('aria-label', showing ? 'Ocultar contraseña' : 'Mostrar contraseña');
                eye.classList.toggle('hidden', showing);
                eyeOff.classList.toggle('hidden', !showing);
            });
        })();
    </script>
</body>
</html>
