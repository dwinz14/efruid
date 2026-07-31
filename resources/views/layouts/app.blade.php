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
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        {{-- Sidebar panel --}}
        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 bg-brand-900 flex flex-col
                   transform transition-transform duration-200 ease-in-out
                   lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-brand-800">
                <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="flex-1">
                    <h1 class="text-lg font-semibold text-slate-900">@yield('page-title', 'Dashboard')</h1>
                </div>

                {{-- Notification bell (placeholder — diimplementasi Fase 7) --}}
                <div class="relative">
                    <button class="btn-ghost p-2 relative" title="Notifikasi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert-success mb-4" role="alert">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-danger mb-4" role="alert">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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

