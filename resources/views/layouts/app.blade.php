<!DOCTYPE html>
<html lang="id" class="h-full antialiased">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eFRUID') — BPR Artha Pamenang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-slate-50 text-slate-800 font-sans selection:bg-brand-500 selection:text-white"
    x-data="{ sidebarOpen: false }">

    {{-- ── Wrapper Utama ── --}}
    <div class="flex h-full overflow-hidden">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"></div>

        {{-- ── Sidebar Panel ── --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 flex flex-col shadow-2xl lg:shadow-none
                   transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-slate-800"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo & Brand Area --}}
            <div class="flex items-center gap-4 px-6 py-6 border-b border-white/5">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-brand-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-bold text-xl tracking-tight leading-none">eFRUID</span>
                    <span class="text-slate-400 text-xs font-medium mt-1">BPR Artha Pamenang</span>
                </div>
            </div>

            {{-- Navigasi Menu --}}
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto sidebar-scroll">
                @include('layouts.partials.sidebar-nav')
            </nav>

            {{-- User Profile Block (Bottom) --}}
            <div class="p-2.5 border-t border-white/5 bg-slate-950/30">
                <div
                    class="group flex items-center gap-2.5 rounded-lg px-2.5 py-2
               bg-slate-800/40 border border-white/[0.05]
               hover:bg-slate-800/70 hover:border-white/[0.08]
               transition-all duration-200">

                    {{-- Avatar --}}
                    <div
                        class="relative flex h-8 w-8 shrink-0 items-center justify-center
                   rounded-lg bg-brand-600 shadow-sm">

                        <span class="text-xs font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>

                        {{-- Online indicator --}}
                        <span
                            class="absolute -right-0.5 -bottom-0.5 h-2.5 w-2.5
                       rounded-full bg-emerald-400 ring-2 ring-slate-800">
                        </span>
                    </div>

                    {{-- User Info --}}
                    <div class="min-w-0 flex-1">

                        <div class="text-xs font-semibold leading-4 text-slate-200
                       group-hover:text-white transition-colors"
                            title="{{ auth()->user()->name ?? 'Pengguna' }}">

                            <span class="line-clamp-1">
                                {{ auth()->user()->name ?? 'Pengguna' }}
                            </span>
                        </div>

                        <div class="text-[11px] leading-4 text-slate-400"
                            title="{{ auth()->user()->kantor?->nama ?? 'BPR Artha Pamenang' }}">

                            <span class="line-clamp-1">
                                {{ auth()->user()->kantor?->nama ?? 'BPR Artha Pamenang' }}
                            </span>
                        </div>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">

                        @csrf

                        <button type="submit"
                            class="flex h-7 w-7 items-center justify-center rounded-md
                       text-slate-500
                       hover:bg-red-500/10 hover:text-red-400
                       focus:outline-none focus:ring-2
                       focus:ring-red-500/40
                       transition-all duration-200"
                            title="Keluar dari sistem" aria-label="Keluar dari sistem">

                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ── Main Content Area ── --}}
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50">

            {{-- Topbar --}}
            <header
                class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm px-4 sm:px-8 py-2 flex items-center gap-4">

                {{-- Mobile Menu Toggle --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 focus:ring-2 focus:ring-brand-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1 flex items-center gap-4">
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard')</h1>
                </div>

                {{-- Notification System --}}
                <div x-data="notificationBell()" x-init="init()" class="relative flex-shrink-0">

                    <script>
                        function notificationBell() {
                            return {
                                open: false,
                                count: 0,
                                notifications: [],
                                loading: false,
                                pollingInterval: null,

                                init() {
                                    this.fetchCount();
                                    this.pollingInterval = setInterval(() => this.fetchCount(), 30000);
                                },

                                async fetchCount() {
                                    try {
                                        const res = await fetch('{{ route('notifications.count') }}', {
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        });
                                        const data = await res.json();
                                        this.count = data.count;
                                    } catch (e) {}
                                },

                                async openBell() {
                                    this.open = !this.open;
                                    if (!this.open) return;

                                    this.loading = true;
                                    try {
                                        const res = await fetch('{{ route('notifications.index') }}', {
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        });
                                        const data = await res.json();
                                        this.notifications = data.notifications;
                                    } catch (e) {}
                                    this.loading = false;
                                },

                                async markRead(id, permohonanId) {
                                    await fetch('{{ route('notifications.read') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'X-Requested-With': 'XMLHttpRequest',
                                        },
                                        body: JSON.stringify({
                                            id
                                        }),
                                    });

                                    const notif = this.notifications.find(n => n.id === id);
                                    if (notif) notif.read = true;
                                    if (this.count > 0) this.count--;

                                    if (permohonanId) {
                                        window.location.href = '/permohonan/' + permohonanId;
                                    }
                                },

                                async markAllRead() {
                                    await fetch('{{ route('notifications.readAll') }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                            'X-Requested-With': 'XMLHttpRequest',
                                        },
                                    });
                                    this.notifications.forEach(n => n.read = true);
                                    this.count = 0;
                                },

                                iconForType(type) {
                                    const icons = {
                                        permohonan_submitted: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                        permohonan_ready_it: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                                        permohonan_need_dirut: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                        permohonan_rejected: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        permohonan_executed: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    };
                                    return icons[type] || icons['permohonan_submitted'];
                                },

                                colorForType(type) {
                                    const colors = {
                                        permohonan_submitted: 'text-brand-600 bg-brand-50',
                                        permohonan_ready_it: 'text-brand-600 bg-brand-50',
                                        permohonan_need_dirut: 'text-purple-600 bg-purple-50',
                                        permohonan_rejected: 'text-red-600 bg-red-50',
                                        permohonan_executed: 'text-green-600 bg-green-50',
                                    };
                                    return colors[type] || 'text-slate-600 bg-slate-100';
                                }
                            }
                        }
                    </script>

                    {{-- Bell Button --}}
                    <button @click="openBell()"
                        class="relative p-2.5 rounded-full text-slate-500 hover:text-brand-600 hover:bg-brand-50 focus:outline-none focus:ring-2 focus:ring-brand-500/50 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>

                        {{-- Badge Count & Pulse effect --}}
                        <template x-if="count > 0">
                            <span class="absolute top-1.5 right-1.5 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                            </span>
                        </template>
                    </button>

                    {{-- Dropdown Notifikasi --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95" @click.outside="open = false"
                        class="absolute right-0 top-full mt-3 w-[360px] bg-white rounded-2xl shadow-2xl border border-slate-100/50 ring-1 ring-slate-900/5 overflow-hidden z-50 origin-top-right"
                        style="display:none;">

                        {{-- Dropdown Header --}}
                        <div
                            class="flex items-center justify-between px-5 py-4 bg-slate-50/80 backdrop-blur-sm border-b border-slate-100">
                            <h3 class="text-sm font-bold text-slate-800">Notifikasi <span x-show="count > 0"
                                    class="ml-1 px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 text-xs font-semibold"
                                    x-text="count"></span></h3>
                            <button x-show="count > 0" @click="markAllRead()"
                                class="text-xs text-brand-600 hover:text-brand-800 font-medium transition-colors">
                                Tandai semua dibaca
                            </button>
                        </div>

                        {{-- Loading State --}}
                        <div x-show="loading" class="px-5 py-8 flex flex-col items-center justify-center bg-white">
                            <svg class="animate-spin w-8 h-8 text-brand-500 mb-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-20" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-100" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span class="text-xs text-slate-400 font-medium">Memuat notifikasi...</span>
                        </div>

                        {{-- List Notifikasi --}}
                        <div x-show="!loading" class="max-h-[380px] overflow-y-auto bg-white custom-scrollbar">

                            {{-- Empty State --}}
                            <template x-if="notifications.length === 0">
                                <div class="px-5 py-12 flex flex-col items-center justify-center text-center">
                                    <div
                                        class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-600">Belum ada notifikasi</p>
                                    <p class="text-xs text-slate-400 mt-1">Anda sudah melihat semuanya.</p>
                                </div>
                            </template>

                            {{-- Item List --}}
                            <template x-for="notif in notifications" :key="notif.id">
                                <button @click="markRead(notif.id, notif.data.permohonan_id)"
                                    class="w-full flex items-start gap-4 px-5 py-4 hover:bg-slate-50 transition-colors text-left border-b border-slate-50 last:border-0 relative group"
                                    :class="notif.read ? 'bg-white opacity-80' : 'bg-brand-50/30'">

                                    {{-- Icon Berwarna --}}
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                                        :class="colorForType(notif.data.type)">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                :d="iconForType(notif.data.type)" />
                                        </svg>
                                    </div>

                                    {{-- Konten Text --}}
                                    <div class="flex-1 min-w-0 pr-4">
                                        <p class="text-sm font-medium text-slate-800 leading-snug group-hover:text-brand-700 transition-colors"
                                            x-text="notif.data.pesan"></p>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                            <span
                                                class="inline-flex items-center text-[11px] font-mono font-medium text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded"
                                                x-text="notif.data.nomor_dokumen"></span>
                                            <span class="text-[11px] text-slate-400 font-medium"
                                                x-text="notif.created_at"></span>
                                        </div>
                                    </div>

                                    {{-- Unread Indicator --}}
                                    <div x-show="!notif.read"
                                        class="absolute right-5 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-brand-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]">
                                    </div>
                                </button>
                            </template>
                        </div>

                        {{-- Footer Dropdown --}}
                        <div class="bg-slate-50 px-5 py-3 text-center border-t border-slate-100">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Menampilkan
                                10 notifikasi terbaru</span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- ── Main Scrollable Area ── --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8 custom-scrollbar">

                <div class="max-w-7xl mx-auto space-y-6">
                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-emerald-50 border-l-4 border-emerald-500 shadow-sm animate-fade-up"
                            role="alert">
                            <div class="bg-emerald-100 p-2 rounded-full flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-red-50 border-l-4 border-red-500 shadow-sm animate-fade-up"
                            role="alert">
                            <div class="bg-red-100 p-2 rounded-full flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-red-800">Terjadi Kesalahan</h3>
                                <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (
                        $errors->any() &&
                            !$errors->has('otp') &&
                            !$errors->has('email') &&
                            !$errors->has('password') &&
                            !$errors->has('current_password') &&
                            !$errors->has('alasan_reject') &&
                            !$errors->has('signature_file') &&
                            !$errors->has('signature_data'))
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-amber-50 border-l-4 border-amber-500 shadow-sm animate-fade-up"
                            role="alert">
                            <div class="bg-amber-100 p-2 rounded-full flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-amber-800">Perhatian</h3>
                                <p class="text-sm text-amber-700 mt-0.5">Terdapat kesalahan pada form. Periksa kembali
                                    isian Anda.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Dynamic Content Area --}}
                    <div class="animate-fade-up" style="animation-delay: 0.1s;">
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
