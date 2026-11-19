@extends('layouts.app')

@section('title', 'Kelola Jabatan')
@section('page-title', 'Master Jabatan')

@section('content')
    <div class="space-y-6" x-data="jabatanManagement()">

        {{-- ── 1. Page Header & Quick Actions ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 rounded-xl border border-indigo-100 shadow-sm flex-shrink-0 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Kelola seluruh jabatan (Tambah, Edit, Hapus)
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    {{ $jabatans->total() }} Jabatan Terdaftar
                </span>

                <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm shadow-indigo-500/25 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Jabatan</span>
                </button>
            </div>
        </div>

        {{-- ── 2. KPI Metric Summary Bar ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 animate-fade-up" style="animation-delay: 0.05s;">
            {{-- Total Jabatan --}}
            <a href="{{ route('admin.jabatan.index') }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ !request()->hasAny(['level', 'is_active']) ? 'bg-slate-900 text-white border-slate-900 shadow-md ring-2 ring-slate-900/10' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-slate-300' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ !request()->hasAny(['level', 'is_active']) ? 'text-slate-300' : 'text-slate-500' }}">Semua
                        Jabatan</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ !request()->hasAny(['level', 'is_active']) ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['total'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ !request()->hasAny(['level', 'is_active']) ? 'text-slate-300' : 'text-slate-400' }}">
                        Seluruh posisi</p>
                </div>
            </a>

            {{-- Direksi (Level 1-2) --}}
            <div class="p-4 rounded-2xl border border-slate-200/80 bg-white shadow-2xs">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-700">Direksi (L1-2)</span>
                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p class="text-2xl font-bold text-purple-950 tracking-tight">{{ number_format($stats['direksi'] ?? 0) }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Direksi</p>
                </div>
            </div>

            {{-- KaBag / Pimcab (Level 3) --}}
            <a href="{{ route('admin.jabatan.index', ['level' => 3]) }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ request('level') === '3' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-600/20' : 'bg-white hover:bg-indigo-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-indigo-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ request('level') === '3' ? 'text-indigo-100' : 'text-indigo-700' }}">KaBag
                        (L3)</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ request('level') === '3' ? 'bg-indigo-700 text-white' : 'bg-indigo-50 text-indigo-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p
                        class="text-2xl font-bold tracking-tight {{ request('level') === '3' ? 'text-white' : 'text-indigo-950' }}">
                        {{ number_format($stats['kabag'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ request('level') === '3' ? 'text-indigo-100' : 'text-slate-400' }}">
                        KABAG & PINCAB</p>
                </div>
            </a>

            {{-- Kasie / Ka. Unit (Level 4) --}}
            <a href="{{ route('admin.jabatan.index', ['level' => 4]) }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ request('level') === '4' ? 'bg-amber-500 text-white border-amber-500 shadow-md ring-2 ring-amber-500/20' : 'bg-white hover:bg-amber-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-amber-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ request('level') === '4' ? 'text-amber-100' : 'text-amber-700' }}">Kasie
                        (L4)</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ request('level') === '4' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p
                        class="text-2xl font-bold tracking-tight {{ request('level') === '4' ? 'text-white' : 'text-amber-950' }}">
                        {{ number_format($stats['kasie'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ request('level') === '4' ? 'text-amber-100' : 'text-slate-400' }}">
                        Kasie & Ka. Unit</p>
                </div>
            </a>

            {{-- Staff & Pelaksana (Level 5) --}}
            <a href="{{ route('admin.jabatan.index', ['level' => 5]) }}"
                class="group p-4 rounded-2xl border transition-all duration-200 {{ request('level') === '5' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-600/20' : 'bg-white hover:bg-emerald-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-emerald-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ request('level') === '5' ? 'text-emerald-100' : 'text-emerald-700' }}">Staff
                        (L5)</span>
                    <div
                        class="w-7 h-7 rounded-lg flex items-center justify-center {{ request('level') === '5' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2.5">
                    <p
                        class="text-2xl font-bold tracking-tight {{ request('level') === '5' ? 'text-white' : 'text-emerald-950' }}">
                        {{ number_format($stats['staff'] ?? 0) }}</p>
                    <p
                        class="text-[11px] mt-0.5 truncate {{ request('level') === '5' ? 'text-emerald-100' : 'text-slate-400' }}">
                        Staff & Pelaksana</p>
                </div>
            </a>

            {{-- Total Pengguna --}}
            <div class="p-4 rounded-2xl border border-slate-200/80 bg-white shadow-2xs">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Pegawai</span>
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
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">Total pemegang jabatan</p>
                </div>
            </div>
        </div>

        {{-- ── 3. Search & Filter Bar ── --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm animate-fade-up"
            style="animation-delay: 0.1s;">
            <form method="GET" action="{{ route('admin.jabatan.index') }}" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    {{-- Input Pencarian --}}
                    <div class="sm:col-span-4">
                        <label class="block mb-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            Pencarian Jabatan
                        </label>

                        <div class="relative">
                            <input name="search" type="text" value="{{ request('search') }}"
                                class="input w-full pl-9 pr-3 rounded-xl border-slate-200 focus:bg-white text-sm"
                                placeholder="Cari nama jabatan...">

                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Filter Level --}}
                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Level Hierarki
                        </label>
                        <select name="level" class="input w-full rounded-xl border-slate-200 text-sm">
                            <option value="">Semua Level</option>
                            @foreach (\App\Models\Jabatan::LEVEL_LABELS as $val => $label)
                                <option value="{{ $val }}" @selected(request('level') !== null && request('level') !== '' && request('level') == $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Status
                        </label>
                        <select name="is_active" class="input w-full rounded-xl border-slate-200 text-sm">
                            <option value="">Semua</option>
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

                        @if (request()->hasAny(['search', 'level', 'is_active']) &&
                                (request('search') || request('level') !== null || request('is_active') !== null))
                            <a href="{{ route('admin.jabatan.index') }}"
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
                @if (request()->hasAny(['search', 'level', 'is_active']) &&
                        (request('search') ||
                            (request('level') !== null && request('level') !== '') ||
                            (request('is_active') !== null && request('is_active') !== '')))
                    <div class="flex items-center gap-2 flex-wrap pt-2 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">Filter terpasang:</span>

                        @if (request('search'))
                            <a href="{{ route('admin.jabatan.index', array_filter(['level' => request('level'), 'is_active' => request('is_active')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                <span>Pencarian: <strong>"{{ request('search') }}"</strong></span>
                                <span class="text-slate-400 font-bold">&times;</span>
                            </a>
                        @endif

                        @if (request('level') !== null && request('level') !== '')
                            @php
                                $levelLabel =
                                    \App\Models\Jabatan::LEVEL_LABELS[(int) request('level')] ?? request('level');
                            @endphp
                            <a href="{{ route('admin.jabatan.index', array_filter(['search' => request('search'), 'is_active' => request('is_active')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-800 border border-indigo-200 hover:bg-indigo-100 transition-colors">
                                <span>Level: <strong>{{ $levelLabel }}</strong></span>
                                <span class="text-indigo-500 font-bold">&times;</span>
                            </a>
                        @endif

                        @if (request('is_active') !== null && request('is_active') !== '')
                            <a href="{{ route('admin.jabatan.index', array_filter(['search' => request('search'), 'level' => request('level')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100 transition-colors">
                                <span>Status:
                                    <strong>{{ request('is_active') === '1' ? 'Aktif' : 'Nonaktif' }}</strong></span>
                                <span class="text-blue-500 font-bold">&times;</span>
                            </a>
                        @endif

                        <a href="{{ route('admin.jabatan.index') }}"
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
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Daftar Jabatan & Alur Otorisasi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan
                        <strong class="text-slate-700">{{ $jabatans->firstItem() ?? 0 }}</strong> -
                        <strong class="text-slate-700">{{ $jabatans->lastItem() ?? 0 }}</strong> dari
                        <strong class="text-slate-700">{{ number_format($jabatans->total()) }}</strong> jabatan
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        class="text-xs font-mono font-semibold text-slate-500 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                        Halaman {{ $jabatans->currentPage() }} / {{ $jabatans->lastPage() }}
                    </span>
                </div>
            </div>

            @if ($jabatans->isEmpty())
                <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-200/80 text-slate-400 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1">Tidak ada data jabatan</h4>
                    <p class="text-xs text-slate-500 max-w-sm mb-4">
                        @if (request()->hasAny(['search', 'level', 'is_active']))
                            Tidak ada jabatan yang sesuai dengan filter pencarian yang diterapkan.
                        @else
                            Belum ada data jabatan yang terdaftar di sistem.
                        @endif
                    </p>
                    <div class="flex items-center gap-2">
                        @if (request()->hasAny(['search', 'level', 'is_active']))
                            <a href="{{ route('admin.jabatan.index') }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="button" @click="openCreate()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Jabatan Sekarang
                        </button>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-4 sm:px-6 w-16">Urutan</th>
                                <th class="py-3.5 px-4">Nama Jabatan</th>
                                <th class="py-3.5 px-4">Level Hierarki</th>
                                <th class="py-3.5 px-4">Pegawai Terdaftar</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($jabatans as $jabatan)
                                @php
                                    $levelBadgeClass = match ($jabatan->level) {
                                        1 => 'bg-purple-50 text-purple-700 border-purple-200',
                                        2 => 'bg-blue-50 text-blue-700 border-blue-200',
                                        3 => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        4 => 'bg-amber-50 text-amber-800 border-amber-200',
                                        5 => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    {{-- Urutan --}}
                                    <td class="py-3.5 px-4 sm:px-6 align-middle">
                                        <span
                                            class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block">
                                            #{{ $jabatan->urutan }}
                                        </span>
                                    </td>

                                    {{-- Nama Jabatan --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-slate-900 text-sm">
                                                {{ $jabatan->nama }}
                                            </p>
                                            @if ($jabatan->is_lainnya)
                                                <span
                                                    class="inline-flex items-center text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200"
                                                    title="Kategori jabatan umum/lainnya">
                                                    Khusus / Lainnya
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Level Hierarki --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold border {{ $levelBadgeClass }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
                                            {{ \App\Models\Jabatan::LEVEL_LABELS[$jabatan->level] ?? 'Level ' . $jabatan->level }}
                                        </span>
                                    </td>

                                    {{-- Pegawai Terdaftar --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        @if ($jabatan->users_count > 0)
                                            <a href="{{ route('admin.users.index', ['jabatan_id' => $jabatan->id]) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold transition-colors"
                                                title="Lihat pegawai dengan jabatan ini">
                                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span>{{ $jabatan->users_count }} Pegawai</span>
                                                <span class="text-[10px] text-brand-500">&rarr;</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-xs italic">
                                                0 Pegawai
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        @if ($jabatan->is_active)
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
                                                @click="openEdit({{ json_encode($jabatan) }}, '{{ route('admin.jabatan.update', $jabatan) }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:text-brand-600 hover:border-brand-300 hover:bg-brand-50/50 shadow-2xs transition-all active:scale-95"
                                                title="Edit data jabatan">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            @if ($jabatan->users_count === 0 && !$jabatan->is_lainnya)
                                                <form method="POST"
                                                    action="{{ route('admin.jabatan.destroy', $jabatan) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jabatan {{ $jabatan->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-rose-600 hover:bg-rose-50 hover:border-rose-200 shadow-2xs transition-all active:scale-95"
                                                        title="Hapus jabatan ini">
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
                                                    title="{{ $jabatan->is_lainnya ? 'Jabatan sistem/khusus tidak dapat dihapus' : 'Jabatan tidak dapat dihapus karena masih digunakan ' . $jabatan->users_count . ' pegawai' }}">
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
                @if ($jabatans->hasPages())
                    <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $jabatans->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- ── 5. Modal Tambah Jabatan Baru ── --}}
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
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Tambah Jabatan Baru</h3>
                            <p class="text-xs text-slate-500">Daftarkan formasi jabatan dan atur level hierarki wewenang
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.jabatan.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Jabatan <span class="text-rose-500">*</span>
                        </label>
                        <input name="nama" type="text" value="{{ old('nama') }}" required
                            class="input w-full uppercase @error('nama') input-error @enderror"
                            placeholder="Contoh: TELLER, CUSTOMER SERVICE, KEPALA CABANG">
                        @error('nama')
                            <p class="field-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Urutan Tampilan <span class="text-rose-500">*</span>
                            </label>
                            <input name="urutan" type="number" value="{{ old('urutan', 50) }}" min="0"
                                max="999" required class="input w-full font-mono text-sm" placeholder="50">
                            <p class="text-[10px] text-slate-400 mt-1">Angka urutan sorting (0 - 999)</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Level Hierarki <span class="text-rose-500">*</span>
                            </label>
                            <select name="level" class="input w-full text-xs font-medium" required>
                                @foreach (\App\Models\Jabatan::LEVEL_LABELS as $val => $label)
                                    <option value="{{ $val }}" @selected(old('level', 5) == $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Menentukan tingkat wewenang & atasan</p>
                        </div>
                    </div>

                    <div
                        class="p-3.5 rounded-xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900 space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Aturan Alur Hierarki Persetujuan FRUID:</span>
                        </p>
                        <p class="text-indigo-800/90 text-[11px] leading-relaxed pl-5">
                            Staff (L5) disetujui Kasie/KaBag &bull; Kasie (L4) disetujui KaBag/Pimcab &bull; KaBag (L3)
                            disetujui Dirut &bull; Direktur (L2) disetujui Dirut.
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-indigo-500/20 transition-all focus:ring-2 focus:ring-indigo-500 active:scale-95">
                            Simpan Jabatan Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── 6. Modal Edit Jabatan ── --}}
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
                            <h3 class="text-base font-bold text-slate-800">Edit Data Jabatan</h3>
                            <p class="text-xs text-slate-500">Perbarui identitas, level hierarki wewenang, dan status</p>
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
                            Nama Jabatan <span class="text-rose-500">*</span>
                        </label>
                        <input name="nama" type="text" x-model="editData.nama" required
                            class="input w-full uppercase" placeholder="NAMA JABATAN">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Urutan Tampilan <span class="text-rose-500">*</span>
                            </label>
                            <input name="urutan" type="number" x-model="editData.urutan" min="0"
                                max="999" required class="input w-full font-mono text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Level Hierarki <span class="text-rose-500">*</span>
                            </label>
                            <select name="level" x-model="editData.level" class="input w-full text-xs font-medium"
                                required>
                                @foreach (\App\Models\Jabatan::LEVEL_LABELS as $val => $label)
                                    <option :value="{{ $val }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Status Operasional
                        </label>
                        <select name="is_active" x-model="editData.is_active" class="input w-full text-xs">
                            <option :value="1">Aktif (Dapat dipilih)</option>
                            <option :value="0">Nonaktif</option>
                        </select>
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
        function jabatanManagement() {
            return {
                showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
                showEditModal: false,
                editData: {
                    id: null,
                    nama: '',
                    urutan: 50,
                    level: 5,
                    is_active: 1,
                    updateUrl: ''
                },
                openCreate() {
                    this.showCreateModal = true;
                },
                openEdit(jabatan, url) {
                    this.editData = {
                        id: jabatan.id,
                        nama: jabatan.nama,
                        urutan: jabatan.urutan,
                        level: jabatan.level,
                        is_active: jabatan.is_active ? 1 : 0,
                        updateUrl: url
                    };
                    this.showEditModal = true;
                }
            }
        }
    </script>
@endsection
