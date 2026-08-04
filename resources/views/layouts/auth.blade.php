<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — eFRUID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-surface font-sans antialiased">

    <div class="flex min-h-full">
        {{-- ── Brand panel (desktop) ── --}}
        <aside
            class="relative hidden flex-col justify-between overflow-hidden bg-brand-950 px-10 py-12 lg:flex lg:w-[46%] xl:w-[42%] xl:px-14 xl:py-16">

            {{-- Ambient background --}}
            <div class="absolute inset-0 bg-grid"></div>
            <div class="absolute -left-40 -top-40 h-[28rem] w-[28rem] rounded-full bg-brand-500/25 blur-3xl"></div>
            <div class="absolute -right-24 -bottom-32 h-[26rem] w-[26rem] rounded-full bg-cyan-400/10 blur-3xl"></div>
            <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-brand-400/10 blur-3xl">
            </div>

            {{-- Logo --}}
            <div class="relative animate-fade-up">
                <div class="flex items-center gap-3.5">
                    <div
                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-500 shadow-lg shadow-brand-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold leading-tight tracking-tight text-white">eFRUID</div>
                        <div class="text-xs text-brand-300">BPR Artha Pamenang</div>
                    </div>
                </div>
            </div>

            {{-- Headline & value proposition --}}
            <div class="relative animate-fade-up" style="animation-delay: 120ms">
                <h2 x-data="typewriter('Sistem Informasi|Formulir Registrasi User ID', 45, 2000)" x-init="start()" x-html="display"
                    class="glow-text text-3xl font-semibold text-white xl:text-4xl">
                </h2>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-brand-300 xl:text-base">
                    Platform permohonan, persetujuan, dan eksekusi permohonan pendaftaran, perubahan, dan penonaktifan
                    User ID pada sistem USSI dalam satu platform yang aman, cepat, dan
                    terintegrasi.
                </p>
            </div>

            {{-- Footer --}}
            <div class="relative animate-fade-up" style="animation-delay: 240ms">
                <p class="text-xs text-brand-400/80">by JustBoyz © {{ date('Y') }} BPR Artha Pamenang — Information
                    Technology.</p>
            </div>
        </aside>

        {{-- ── Form panel ── --}}
        <main class="flex flex-1 flex-col items-center justify-center px-4 py-10 sm:px-6">
            <div class="mx-auto max-w-6xl">

                {{-- Brand header (mobile) --}}
                <div class="mb-8 flex items-center justify-center gap-2.5 lg:hidden">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 shadow-md shadow-brand-600/25">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-bold leading-tight text-slate-900">eFRUID</div>
                        <div class="text-xs text-slate-400">BPR Artha Pamenang</div>
                    </div>
                </div>

                {{-- Card konten --}}
                <div class="group relative animate-fade-up">

                    <div
                        class="absolute inset-0 -z-10 rounded-[2rem] bg-gradient-to-br from-blue-500/20 via-indigo-500/15 to-cyan-400/15 blur-3xl scale-105 transition-all duration-500 group-hover:scale-110 group-hover:opacity-100">
                    </div>

                    <div
                        class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 lg:p-10 shadow-[0_12px_40px_rgba(0,0,0,0.08)] transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-[0_20px_60px_rgba(0,0,0,0.12)]">
                        @yield('content')
                    </div>

                </div>

                <p class="mt-6 text-center text-xs text-slate-400 lg:hidden">
                    by JustBoyz © {{ date('Y') }} BPR Artha Pamenang — Information Technology.
                </p>
            </div>
        </main>
    </div>
</body>

</html>
