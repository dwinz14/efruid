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

<body class="h-full bg-slate-50 text-slate-800 font-sans selection:bg-brand-500 selection:text-white"
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
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-brand-700 focus:shadow-lg">
        Lewati ke konten utama
    </a>

    {{-- ── Wrapper Utama ── --}}
    <div class="flex h-full overflow-hidden">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-cloak aria-hidden="true"
            x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="closeSidebar()"></div>

        {{-- ── Sidebar Panel ── --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-800 bg-slate-900 lg:static"
            :data-open="sidebarOpen ? 'true' : 'false'">

            {{-- Brand --}}
            <div class="flex items-center gap-3 border-b border-white/5 px-4 py-5">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-600">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-lg font-bold leading-none tracking-tight text-white">eFRUID</p>
                    <p class="mt-1 truncate text-xs text-slate-400">BPR Artha Pamenang</p>
                </div>
                <button type="button" x-ref="sidebarClose" @click="closeSidebar()" aria-label="Tutup menu"
                    class="-mr-1 rounded-lg p-2 text-slate-400 transition-colors hover:bg-white/5 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigasi --}}
            <nav aria-label="Menu utama" class="flex-1 overflow-y-auto px-3 py-4 sidebar-scroll">
                @include('layouts.partials.sidebar-nav')
            </nav>

            {{-- Profil & keluar --}}
            @php
                $authUser = auth()->user();
                $onProfile = request()->routeIs('profile.*');
            @endphp
            <div class="flex items-center gap-1 border-t border-white/5 p-3">
                <a href="{{ route('profile.edit') }}" title="Profil saya"
                    @if ($onProfile) aria-current="page" @endif
                    class="flex min-w-0 flex-1 items-center gap-3 rounded-lg px-2 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 {{ $onProfile ? 'bg-brand-800' : 'hover:bg-white/5' }}">
                    <span
                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-brand-600 text-sm font-semibold text-white"
                        aria-hidden="true">{{ mb_strtoupper(mb_substr($authUser->name ?? 'U', 0, 1)) }}</span>
                    <span class="min-w-0 flex-1">
                        <span
                            class="block truncate text-sm font-medium text-white">{{ $authUser->name ?? 'Pengguna' }}</span>
                        <span
                            class="block truncate text-xs text-slate-400">{{ $authUser->kantor?->nama ??
                                'BPR Artha
                                                                                                                Pamenang' }}</span>
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Keluar dari sistem" aria-label="Keluar dari sistem"
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-red-500/10 hover:text-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Main Content Area ── --}}
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50">

            {{-- Topbar --}}
            <header
                class="relative z-30 flex flex-shrink-0 items-center gap-3 border-b border-slate-200 bg-white px-4 py-2 sm:px-8">

                {{-- Mobile Menu Toggle --}}
                <button type="button" x-ref="menuButton" @click="openSidebar()" aria-label="Buka menu"
                    aria-controls="sidebar" :aria-expanded="sidebarOpen.toString()"
                    class="-ml-2 rounded-lg p-2 text-slate-600 transition-colors hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex min-w-0 flex-1 items-center">
                    <h1 class="truncate text-lg font-semibold sm:text-xl">@yield('page-title', 'Dashboard')</h1>
                </div>

                @include('layouts.partials.notification-bell')
            </header>

            @php
                // Lebar konten: @section('width', 'sm|md|lg'). Tanpa section = penuh (7xl).
                $containerWidth = match (trim($__env->yieldContent('width'))) {
                    'sm' => 'max-w-2xl',
                    'md' => 'max-w-3xl',
                    'lg' => 'max-w-5xl',
                    default => 'max-w-7xl',
                };
            @endphp
            {{-- ── Main Scrollable Area ── --}}
            <main id="konten" tabindex="-1"
                class="flex-1 overflow-y-auto overflow-x-hidden p-4 focus:outline-none sm:p-6 lg:p-8 custom-scrollbar">

                <div class="{{ $containerWidth }} mx-auto space-y-6">
                    {{-- Flash Messages --}}
                    @include('layouts.partials.flash')

                    {{-- Dynamic Content Area --}}
                    <div>
                        @yield('content')
                    </div>
                </div>

            </main>


            <footer class="bg-white border-t border-slate-200 mt-auto flex-shrink-0">
                <div
                    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <span class="text-xs font-medium text-slate-500">&copy; {{ date('Y') }} Built by<span
                            class="text-slate-800 font-semibold"> .Emptiness</span></span>
                    <div class="flex items-center gap-4">
                        <span
                            class="text-[11px] font-mono font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">v1.0.0</span>
                        <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-bold text-emerald-700 tracking-wide">eFRUID</span>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    {{-- Modal stack for portal/root-level dialogs --}}
    @stack('modals')

    {{-- Toast component (Alpine.js) --}}
    @include('layouts.partials.toast')

    @stack('scripts')

    {{-- CSS Tambahan Khusus untuk Scrollbar Area --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
    </style>
</body>

</html>
