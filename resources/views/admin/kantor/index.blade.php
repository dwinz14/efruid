@extends('layouts.app')

@section('title', 'Kelola Kantor')
@section('page-title', 'Master Kantor')

@section('content')
    <div class="space-y-6" x-data="kantorManagement()">

        {{-- ── 1. Page Header & Quick Actions ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div
                    class="p-2.5 bg-emerald-50 rounded-xl border border-emerald-100 shadow-sm flex-shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Kelola seluruh kantor (Tambah, Edit, Hapus)
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $kantors->total() }} Kantor Terdaftar
                </span>

                <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm shadow-emerald-500/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Kantor</span>
                </button>
            </div>
        </div>

        {{-- ── 2. KPI Metric Cards ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 animate-fade-up" style="animation-delay: 0.05s;">
            {{-- Total Kantor --}}
            <a href="{{ route('admin.kantor.index') }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ !request()->hasAny(['is_pusat', 'is_active']) ? 'bg-slate-900 text-white border-slate-900 shadow-md ring-2 ring-slate-900/10' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-slate-300' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ !request()->hasAny(['is_pusat', 'is_active']) ? 'text-slate-300' : 'text-slate-500' }}">Semua
                        Kantor</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ !request()->hasAny(['is_pusat', 'is_active']) ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['total'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ !request()->hasAny(['is_pusat', 'is_active']) ? 'text-slate-300' : 'text-slate-400' }}">
                        Seluruh unit kantor</p>
                </div>
            </a>

            {{-- Kantor Pusat --}}
            <a href="{{ route('admin.kantor.index', ['is_pusat' => 1]) }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ request('is_pusat') === '1' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-600/20' : 'bg-white hover:bg-emerald-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-emerald-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ request('is_pusat') === '1' ? 'text-emerald-100' : 'text-emerald-700' }}">Kantor
                        Pusat</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ request('is_pusat') === '1' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p
                        class="text-2xl font-bold tracking-tight {{ request('is_pusat') === '1' ? 'text-white' : 'text-emerald-950' }}">
                        {{ number_format($stats['pusat'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ request('is_pusat') === '1' ? 'text-emerald-100' : 'text-slate-400' }}">
                        Headquarters (KP)</p>
                </div>
            </a>

            {{-- Kantor Cabang --}}
            <a href="{{ route('admin.kantor.index', ['is_pusat' => 0]) }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ request('is_pusat') === '0' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-600/20' : 'bg-white hover:bg-indigo-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-indigo-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ request('is_pusat') === '0' ? 'text-indigo-100' : 'text-indigo-700' }}">Kantor
                        Cabang</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ request('is_pusat') === '0' ? 'bg-indigo-700 text-white' : 'bg-indigo-50 text-indigo-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p
                        class="text-2xl font-bold tracking-tight {{ request('is_pusat') === '0' ? 'text-white' : 'text-indigo-950' }}">
                        {{ number_format($stats['cabang'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ request('is_pusat') === '0' ? 'text-indigo-100' : 'text-slate-400' }}">
                        Jaringan cabang</p>
                </div>
            </a>

            {{-- Status Aktif --}}
            <a href="{{ route('admin.kantor.index', ['is_active' => 1]) }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ request('is_active') === '1' ? 'bg-teal-600 text-white border-teal-600 shadow-md ring-2 ring-teal-600/20' : 'bg-white hover:bg-teal-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-teal-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ request('is_active') === '1' ? 'text-teal-100' : 'text-teal-700' }}">Aktif
                        Operasional</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ request('is_active') === '1' ? 'bg-teal-700 text-white' : 'bg-teal-50 text-teal-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p
                        class="text-2xl font-bold tracking-tight {{ request('is_active') === '1' ? 'text-white' : 'text-teal-950' }}">
                        {{ number_format($stats['aktif'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ request('is_active') === '1' ? 'text-teal-100' : 'text-slate-400' }}">
                        Dapat dipilih di form</p>
                </div>
            </a>

            {{-- Total Alokasi Pegawai --}}
            <div class="p-4 rounded-2xl border border-slate-200/80 bg-white shadow-2xs">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pegawai Terdaftar</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p class="text-2xl font-bold text-slate-800 tracking-tight">
                        {{ number_format($stats['total_users'] ?? 0) }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Total seluruh penempatan</p>
                </div>
            </div>
        </div>

        {{-- ── 3. Search & Filter Bar ── --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm animate-fade-up"
            style="animation-delay: 0.1s;">
            <form method="GET" action="{{ route('admin.kantor.index') }}" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    {{-- Input Pencarian --}}
                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Pencarian Kantor
                        </label>
                        <div class="relative">
                            <input name="search" type="text" value="{{ request('search') }}"
                                class="input w-full pl-9 pr-3 rounded-xl border-slate-200 focus:bg-white text-sm"
                                placeholder="Cari nama kantor atau kode...">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Tipe --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Tipe Kantor
                        </label>
                        <select name="is_pusat" class="input w-full rounded-xl border-slate-200 text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="1" @selected(request('is_pusat') === '1')>Kantor Pusat</option>
                            <option value="0" @selected(request('is_pusat') === '0')>Kantor Cabang</option>
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Status
                        </label>
                        <select name="is_active" class="input w-full rounded-xl border-slate-200 text-sm">
                            <option value="">Semua Status</option>
                            <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                            <option value="0" @selected(request('is_active') === '0')>Nonaktif</option>
                        </select>
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="sm:col-span-2 flex items-center gap-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-brand-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        @if (request()->hasAny(['search', 'is_pusat', 'is_active']) &&
                                (request('search') || request('is_pusat') !== null || request('is_active') !== null))
                            <a href="{{ route('admin.kantor.index') }}"
                                class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300"
                                title="Reset filter">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Active Filter Chips --}}
                @if (request()->hasAny(['search', 'is_pusat', 'is_active']) &&
                        (request('search') || request('is_pusat') !== null || request('is_active') !== null))
                    <div class="flex items-center gap-2 flex-wrap pt-2 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">Filter terpasang:</span>

                        @if (request('search'))
                            <a href="{{ route('admin.kantor.index', array_filter(['is_pusat' => request('is_pusat'), 'is_active' => request('is_active')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                <span>Pencarian: <strong>"{{ request('search') }}"</strong></span>
                                <span class="text-slate-400 font-bold">&times;</span>
                            </a>
                        @endif

                        @if (request('is_pusat') !== null && request('is_pusat') !== '')
                            <a href="{{ route('admin.kantor.index', array_filter(['search' => request('search'), 'is_active' => request('is_active')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                <span>Tipe:
                                    <strong>{{ request('is_pusat') === '1' ? 'Kantor Pusat' : 'Kantor Cabang' }}</strong></span>
                                <span class="text-emerald-500 font-bold">&times;</span>
                            </a>
                        @endif

                        @if (request('is_active') !== null && request('is_active') !== '')
                            <a href="{{ route('admin.kantor.index', array_filter(['search' => request('search'), 'is_pusat' => request('is_pusat')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100 transition-colors">
                                <span>Status:
                                    <strong>{{ request('is_active') === '1' ? 'Aktif' : 'Nonaktif' }}</strong></span>
                                <span class="text-blue-500 font-bold">&times;</span>
                            </a>
                        @endif

                        <a href="{{ route('admin.kantor.index') }}"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 ml-1 hover:underline">
                            Hapus Semua
                        </a>
                    </div>
                @endif
            </form>
        </div>

        {{-- ── 4. Main Data Table ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.15s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Daftar Kantor Operasional</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan
                        <strong class="text-slate-700">{{ $kantors->firstItem() ?? 0 }}</strong> -
                        <strong class="text-slate-700">{{ $kantors->lastItem() ?? 0 }}</strong> dari
                        <strong class="text-slate-700">{{ number_format($kantors->total()) }}</strong> kantor
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        class="text-xs font-mono font-semibold text-slate-500 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                        Halaman {{ $kantors->currentPage() }} / {{ $kantors->lastPage() }}
                    </span>
                </div>
            </div>

            @if ($kantors->isEmpty())
                <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-200/80 text-slate-400 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1">Tidak ada data kantor</h4>
                    <p class="text-xs text-slate-500 max-w-sm mb-4">
                        @if (request()->hasAny(['search', 'is_pusat', 'is_active']))
                            Tidak ada kantor yang sesuai dengan filter pencarian yang diterapkan.
                        @else
                            Belum ada data kantor yang terdaftar di sistem.
                        @endif
                    </p>
                    <div class="flex items-center gap-2">
                        @if (request()->hasAny(['search', 'is_pusat', 'is_active']))
                            <a href="{{ route('admin.kantor.index') }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="button" @click="openCreate()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Kantor Sekarang
                        </button>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-4 sm:px-6">Nama & Identitas Kantor</th>
                                <th class="py-3.5 px-4">Kode Kantor</th>
                                <th class="py-3.5 px-4">Tipe Organisasi</th>
                                <th class="py-3.5 px-4">Alokasi Pegawai</th>
                                <th class="py-3.5 px-4">Status Operasional</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($kantors as $kantor)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    {{-- Nama Kantor --}}
                                    <td class="py-3.5 px-4 sm:px-6 align-middle">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $kantor->is_pusat ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                @if ($kantor->is_pusat)
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm">
                                                    {{ $kantor->nama }}
                                                </p>
                                                <p class="text-[11px] text-slate-400 mt-0.5">
                                                    {{ $kantor->label }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kode --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <span
                                            class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                                            {{ $kantor->kode }}
                                        </span>
                                    </td>

                                    {{-- Tipe --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        @if ($kantor->is_pusat)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Kantor Pusat
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Kantor Cabang
                                            </span>
                                        @endif
                                    </td>

                                    {{-- User Count --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        @if ($kantor->users_count > 0)
                                            <a href="{{ route('admin.users.index', ['kantor_id' => $kantor->id]) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold transition-colors"
                                                title="Lihat daftar pegawai di kantor ini">
                                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span>{{ $kantor->users_count }} Pegawai</span>
                                                <span class="text-[10px] text-brand-500">&rarr;</span>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-slate-400 text-xs italic">
                                                <span>0 Pegawai</span>
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        @if ($kantor->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-3.5 px-4 sm:px-6 align-middle text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Tombol Edit --}}
                                            <button type="button"
                                                @click="openEdit({{ json_encode($kantor) }}, '{{ route('admin.kantor.update', $kantor) }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:text-brand-600 hover:border-brand-300 hover:bg-brand-50/50 shadow-2xs transition-all active:scale-95"
                                                title="Edit data kantor">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            @if ($kantor->users_count === 0)
                                                <form method="POST"
                                                    action="{{ route('admin.kantor.destroy', $kantor) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kantor {{ $kantor->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-rose-600 hover:bg-rose-50 hover:border-rose-200 shadow-2xs transition-all active:scale-95"
                                                        title="Hapus kantor ini">
                                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span
                                                    class="inline-flex items-center p-1.5 text-slate-300 cursor-not-allowed"
                                                    title="Kantor tidak dapat dihapus karena masih memiliki {{ $kantor->users_count }} pegawai">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($kantors->hasPages())
                    <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $kantors->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- ── 5. Modal Tambah Kantor Baru ── --}}
        <div x-show="showCreateModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                @click="showCreateModal = false"></div>

            {{-- Modal Dialog --}}
            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 z-10 animate-fade-up"
                @keydown.escape.window="showCreateModal = false">

                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Tambah Kantor Baru</h3>
                            <p class="text-xs text-slate-500">Daftarkan unit kerja kantor pusat atau cabang baru</p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.kantor.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Kantor <span class="text-rose-500">*</span>
                        </label>
                        <input name="nama" type="text" value="{{ old('nama') }}" required
                            class="input w-full uppercase @error('nama') input-error @enderror"
                            placeholder="Contoh: SURABAYA, BLITAR, PUSAT">
                        @error('nama')
                            <p class="field-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700">
                                Kode Kantor <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono">Maks 10 karakter alfanumerik</span>
                        </div>
                        <input name="kode" type="text" value="{{ old('kode') }}" maxlength="10" required
                            class="input w-full font-mono uppercase @error('kode') input-error @enderror"
                            placeholder="Contoh: SBY, BLT, PST">
                        @error('kode')
                            <p class="field-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="is_pusat" id="create_is_pusat" value="1"
                                @checked(old('is_pusat'))
                                class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                            <div>
                                <label for="create_is_pusat" class="text-xs font-bold text-slate-800 cursor-pointer">
                                    Tetapkan sebagai Kantor Pusat (Headquarters)
                                </label>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                    Kantor pusat memiliki perlakuan khusus pada alur verifikasi hierarki organisasi. Jika
                                    tidak dicentang, akan berstatus Kantor Cabang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-emerald-500/20 transition-all focus:ring-2 focus:ring-emerald-500 active:scale-95">
                            Simpan Kantor Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── 6. Modal Edit Kantor ── --}}
        <div x-show="showEditModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showEditModal = false">
            </div>

            {{-- Modal Dialog --}}
            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 z-10 animate-fade-up"
                @keydown.escape.window="showEditModal = false">

                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Edit Data Kantor</h3>
                            <p class="text-xs text-slate-500">Perbarui identitas, kode, tipe, dan status kantor</p>
                        </div>
                    </div>
                    <button type="button" @click="showEditModal = false"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="editData.updateUrl" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Kantor <span class="text-rose-500">*</span>
                        </label>
                        <input name="nama" type="text" x-model="editData.nama" required
                            class="input w-full uppercase" placeholder="NAMA KANTOR">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700">
                                Kode Kantor <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono">Maks 10 karakter</span>
                        </div>
                        <input name="kode" type="text" x-model="editData.kode" maxlength="10" required
                            class="input w-full font-mono uppercase" placeholder="KODE">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tipe Organisasi
                            </label>
                            <select name="is_pusat" x-model="editData.is_pusat" class="input w-full text-xs">
                                <option :value="1">Kantor Pusat (Headquarters)</option>
                                <option :value="0">Kantor Cabang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Status Operasional
                            </label>
                            <select name="is_active" x-model="editData.is_active" class="input w-full text-xs">
                                <option :value="1">Aktif Operasional</option>
                                <option :value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-brand-500/20 transition-all focus:ring-2 focus:ring-brand-500 active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Script Alpine.js --}}
    <script>
        function kantorManagement() {
            return {
                showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
                showEditModal: false,
                editData: {
                    id: null,
                    nama: '',
                    kode: '',
                    is_pusat: 0,
                    is_active: 1,
                    updateUrl: ''
                },
                openCreate() {
                    this.showCreateModal = true;
                },
                openEdit(kantor, url) {
                    this.editData = {
                        id: kantor.id,
                        nama: kantor.nama,
                        kode: kantor.kode,
                        is_pusat: kantor.is_pusat ? 1 : 0,
                        is_active: kantor.is_active ? 1 : 0,
                        updateUrl: url
                    };
                    this.showEditModal = true;
                }
            }
        }
    </script>
@endsection
