<!DOCTYPE html>
<html lang="id" class="h-full antialiased">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eFRUID') — BPR Artha Pamenang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <style>
        @view-transition {
            navigation: auto;
        }

        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation-duration: 0.18s;
        }

        @media (prefers-reduced-motion: reduce) {

            ::view-transition-group(*),
            ::view-transition-old(*),
            ::view-transition-new(*) {
                animation: none !important;
            }
        }
    </style>
</head>

<body class="h-full overflow-hidden bg-surface font-sans text-slate-800 selection:bg-brand-500 selection:text-white"
    x-data="{
        sidebarOpen: false,
        isDesktop: window.matchMedia('(min-width: 1024px)').matches,
        openSidebar() {
            this.sidebarOpen = true;
            this.$nextTick(() => this.$refs.sidebarClose?.focus());
        },
        closeSidebar() {
            this.sidebarOpen = false;
            this.$nextTick(() => this.$refs.menuButton?.focus());
        },
    }" x-init="window.matchMedia('(min-width: 1024px)').addEventListener('change', e => { isDesktop = e.matches; if (e.matches) sidebarOpen = false; })" @keydown.escape.window="sidebarOpen && closeSidebar()">
    <a href="#konten"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-brand-700 focus:shadow-lg">
        Lewati ke konten utama
    </a>

    @php
        // Judul & breadcrumb. Section inline (@section('x', '...')) sudah di-escape Blade,
        // jadi di-decode dulu agar tidak ter-escape dua kali saat dicetak ulang dengan {{ }}.
        $decode = fn(string $v): string => html_entity_decode(trim($v), ENT_QUOTES);

        $pageTitle = $decode($__env->yieldContent('page-title')) ?: 'Dashboard';
        $parentTitle = $decode($__env->yieldContent('parent'));
        $parentUrl = $decode($__env->yieldContent('parent-url'));

        $crumbs = [];
        if (!request()->routeIs('dashboard*')) {
            $crumbs[] = ['label' => 'Dashboard', 'url' => route('dashboard')];
        }
        if ($parentTitle !== '') {
            $crumbs[] = ['label' => $parentTitle, 'url' => $parentUrl ?: null];
        }
        $crumbs[] = ['label' => $pageTitle];

        // Lebar konten: @section('width', 'sm|md|lg'). Tanpa section = penuh (7xl).
        $containerWidth = match (trim($__env->yieldContent('width'))) {
            'sm' => 'max-w-2xl',
            'md' => 'max-w-3xl',
            'lg' => 'max-w-5xl',
            default => 'max-w-7xl',
        };

        $authUser = auth()->user();
        $onProfile = request()->routeIs('profile.*');
    @endphp

    {{-- ── Wrapper Utama (kanvas): ≥ lg sidebar & konten berupa panel melayang ── --}}
    <div class="flex h-dvh min-h-0 overflow-hidden lg:gap-4 lg:p-4">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-cloak aria-hidden="true"
            x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="closeSidebar()"></div>

        {{-- ── Sidebar Panel (biru dongker) ── --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col rounded-r-card bg-brand-900 shadow-float lg:static lg:z-auto lg:flex-shrink-0 lg:rounded-card"
            :data-open="sidebarOpen ? 'true' : 'false'">

            {{-- Brand --}}
            <div class="flex items-center gap-3 border-b border-white/10 px-5 py-5">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-brand-600">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-lg font-bold leading-none tracking-tight text-white">eFRUID</p>
                    <p class="mt-1 truncate text-xs text-brand-300">BPR Artha Pamenang</p>
                </div>
                <button type="button" x-ref="sidebarClose" @click="closeSidebar()" aria-label="Tutup menu"
                    class="-mr-1 rounded-full p-2 text-brand-200 transition-colors hover:bg-white/10 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigasi --}}
            <nav aria-label="Menu utama" class="flex-1 overflow-y-auto px-4 py-4 sidebar-scroll">
                @include('layouts.partials.sidebar-nav')
            </nav>

            {{-- Profil & keluar --}}
            <div class="flex items-center gap-1 border-t border-white/10 p-3">
                <a href="{{ route('profile.edit') }}" title="Profil saya"
                    @if ($onProfile) aria-current="page" @endif
                    class="flex min-w-0 flex-1 items-center gap-3 rounded-full py-2 pl-2 pr-3 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 {{ $onProfile ? 'bg-white/10' : 'hover:bg-white/10' }}">
                    <span
                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-semibold text-white"
                        aria-hidden="true">{{ mb_strtoupper(mb_substr($authUser->name ?? 'U', 0, 1)) }}</span>
                    <span class="min-w-0 flex-1">
                        <span
                            class="block truncate text-sm font-medium text-white">{{ $authUser->name ?? 'Pengguna' }}</span>
                        <span
                            class="block truncate text-xs text-brand-300">{{ $authUser->kantor?->nama ?? 'BPR Artha Pamenang' }}</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Keluar dari sistem" aria-label="Keluar dari sistem"
                        class="flex h-10 w-10 items-center justify-center rounded-full text-brand-200 transition-colors hover:bg-red-500/15 hover:text-red-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Kolom Utama ── --}}
        <div class="flex min-h-0 min-w-0 flex-1 flex-col">

            {{-- Topbar: transparan di atas kanvas --}}
            <header class="relative z-30 flex flex-shrink-0 items-center gap-3 px-4 py-3 sm:px-6 lg:px-4">

                {{-- Tombol menu (mobile) --}}
                <button type="button" x-ref="menuButton" @click="openSidebar()" aria-label="Buka menu"
                    aria-controls="sidebar" :aria-expanded="sidebarOpen.toString()"
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white text-slate-700 shadow-card transition-colors hover:text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Tombol kembali: hanya jika halaman menetapkan @section('parent-url') --}}
                @if ($parentUrl !== '')
                    <a href="{{ $parentUrl }}"
                        aria-label="Kembali{{ $parentTitle !== '' ? ' ke ' . $parentTitle : '' }}"
                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white text-slate-700 shadow-card transition-colors hover:text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                <x-breadcrumb :items="$crumbs" class="flex-1" />

                {{-- Gugus aksi kanan --}}
                <div class="flex-shrink-0 rounded-full bg-white p-0.5 shadow-card">
                    @include('layouts.partials.notification-bell')
                </div>
            </header>

            {{-- ── Area konten (scroll) ── --}}
            <main id="konten" tabindex="-1"
                class="custom-scrollbar flex min-h-0 flex-1 flex-col overflow-x-hidden overflow-y-auto overscroll-contain px-4 pb-2 pt-2 focus:outline-none sm:px-6 lg:px-4">

                <div class="{{ $containerWidth }} mx-auto w-full flex-1 space-y-6">
                    {{-- Baris judul: judul halaman + (opsional) angka kunci di kanan --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <h1 class="min-w-0 text-2xl font-semibold tracking-tight text-brand-950 sm:text-3xl">
                            {{ $pageTitle }}</h1>

                        @hasSection('page-stats')
                            <div class="flex flex-shrink-0 flex-wrap items-end gap-x-8 gap-y-3">
                                @yield('page-stats')
                            </div>
                        @endif
                    </div>

                    {{-- Flash Messages --}}
                    @include('layouts.partials.flash')

                    {{-- Dynamic Content Area --}}
                    <div>
                        @yield('content')
                    </div>
                </div>

                {{-- Footer ringkas --}}
                <footer
                    class="{{ $containerWidth }} mx-auto mt-auto w-full flex flex-wrap items-center justify-between gap-2 pt-6 text-xs text-slate-600">
                    <span>&copy; {{ date('Y') }} Built by <span
                            class="font-semibold text-slate-800">.Emptiness</span></span>
                    <span
                        class="rounded-full bg-white px-2.5 py-1 font-mono font-semibold text-slate-600">v1.0.0</span>
                </footer>
            </main>
        </div>
    </div>

    {{-- Modal stack for portal/root-level dialogs --}}
    @stack('modals')

    {{-- Toast component (Alpine.js) --}}
    @include('layouts.partials.toast')

    @stack('scripts')
</body>

</html>
