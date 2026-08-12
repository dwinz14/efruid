<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eFRUID') — BPR Artha Pamenang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-surface" x-data="{ sidebarOpen: false }">

    {{-- ── Sidebar ── --}}
    <div class="flex h-full">

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"></div>

        {{-- Sidebar panel --}}
        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 bg-brand-900 flex flex-col
                   transform transition-transform duration-200 ease-in-out
                   lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-brand-800">
                <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-white font-bold text-base leading-tight">eFRUID</div>
                    <div class="text-brand-300 text-xs">BPR Artha Pamenang</div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @include('layouts.partials.sidebar-nav')
            </nav>

            {{-- User info --}}
            <div class="px-3 py-4 border-t border-brand-800">
                <div class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-brand-800 transition-colors">
                    <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-xs font-medium truncate">
                            {{ auth()->user()->name ?? '' }}
                        </div>
                        <div class="text-brand-400 text-xs truncate">
                            {{ auth()->user()->kantor?->nama ?? '' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-brand-400 hover:text-white transition-colors" title="Logout">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ── Main content ── --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Topbar --}}
            <header class="bg-white border-b border-surface-border px-4 py-3 flex items-center gap-4 flex-shrink-0">
                {{-- Mobile menu toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden btn-ghost p-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1">
                    <h1 class="text-lg font-semibold text-slate-900">@yield('page-title', 'Dashboard')</h1>
                </div>

                {{-- Notification bell --}}
                <div x-data="notificationBell()" x-init="init()" class="relative">

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
                                    // Polling setiap 30 detik
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

                                    // Update local state
                                    const notif = this.notifications.find(n => n.id === id);
                                    if (notif) notif.read = true;
                                    if (this.count > 0) this.count--;

                                    // Navigasi ke detail permohonan
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
                                        permohonan_submitted: 'text-brand-500',
                                        permohonan_ready_it: 'text-brand-500',
                                        permohonan_need_dirut: 'text-purple-500',
                                        permohonan_rejected: 'text-red-500',
                                        permohonan_executed: 'text-green-500',
                                    };
                                    return colors[type] || 'text-slate-500';
                                }
                            }
                        }
                    </script>

                    {{-- Bell button --}}
                    <button @click="openBell()" class="btn-ghost p-2 relative" title="Notifikasi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                     a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                     C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055
                     -.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        {{-- Badge count --}}
                        <span x-show="count > 0" x-text="count > 99 ? '99+' : count" class="notif-badge"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @click.outside="open = false" class="notif-dropdown" style="display:none">
                        {{-- Dropdown header --}}
                        <div
                            class="flex items-center justify-between px-4 py-3
                    border-b border-surface-border">
                            <h3 class="text-sm font-semibold text-slate-800">Notifikasi</h3>
                            <button x-show="count > 0" @click="markAllRead()"
                                class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                                Tandai semua dibaca
                            </button>
                        </div>

                        {{-- Loading --}}
                        <div x-show="loading" class="px-4 py-6 text-center">
                            <svg class="animate-spin w-5 h-5 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                        </div>

                        {{-- List notifikasi --}}
                        <div x-show="!loading" class="max-h-80 overflow-y-auto">

                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-8 text-center">
                                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118
                                 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0
                                 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159
                                 c0 .538-.214 1.055-.595 1.436L4 17h5m6
                                 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="text-sm text-slate-400">Tidak ada notifikasi</p>
                                </div>
                            </template>

                            <template x-for="notif in notifications" :key="notif.id">
                                <button @click="markRead(notif.id, notif.data.permohonan_id)" class="notif-item"
                                    :class="notif.read ? 'notif-item-read' : 'notif-item-unread'">
                                    {{-- Icon --}}
                                    <div class="notif-item-icon">
                                        <svg class="w-4 h-4" :class="colorForType(notif.data.type)" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                :d="iconForType(notif.data.type)" />
                                        </svg>
                                    </div>

                                    {{-- Konten --}}
                                    <div class="notif-item-content">
                                        <p class="text-xs font-medium text-slate-800 text-left"
                                            x-text="notif.data.pesan">
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-mono text-slate-400"
                                                x-text="notif.data.nomor_dokumen">
                                            </span>
                                            <span class="text-xs text-slate-400" x-text="notif.created_at">
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Unread dot --}}
                                    <div x-show="!notif.read" class="notif-unread-dot"></div>
                                </button>
                            </template>
                        </div>

                        {{-- Footer --}}
                        <div class="border-t border-surface-border px-4 py-2 text-center">
                            <span class="text-xs text-slate-400">
                                Menampilkan 10 notifikasi terbaru
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="alert-success mb-4" role="alert">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-danger mb-4" role="alert">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Toast component (Alpine.js) --}}
    @include('layouts.partials.toast')

    @stack('scripts')
</body>

</html>
