@extends('layouts.app')

@section('title', 'Monitoring Semua Permohonan')
@section('page-title', 'Monitoring Permohonan')

@section('content')
    <div class="space-y-6">

        {{-- ── 1. Top Header & Monitoring Banner ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-brand-50 rounded-xl border border-brand-100 shadow-sm flex-shrink-0 text-brand-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Pusat pengawasan dan audit permohonan hak akses USSI di seluruh unit kerja & kantor operasional
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
                <span
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                    Total: {{ number_format($permohonan->total()) }} Dokumen
                </span>

                @if (request()->hasAny(['search', 'status', 'kantor_id']) &&
                        (request('search') || request('status') || request('kantor_id')))
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Aktif
                    </span>
                @endif
            </div>
        </div>

        {{-- ── 2. KPI Quick Filter Cards (Ringkasan Status Interaktif) ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 animate-fade-up" style="animation-delay: 0.05s;">
            {{-- Card 1: Semua --}}
            @php
                $isAllActive = !request('status');
                $urlAll = route(
                    'admin.permohonan.index',
                    array_filter(['search' => request('search'), 'kantor_id' => request('kantor_id')]),
                );
            @endphp
            <a href="{{ $urlAll }}"
                class="group p-3.5 rounded-2xl border transition-all duration-200 {{ $isAllActive ? 'bg-slate-900 text-white border-slate-900 shadow-md ring-2 ring-slate-900/10' : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-slate-300' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ $isAllActive ? 'text-slate-300' : 'text-slate-500' }}">Semua</span>
                    <div
                        class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isAllActive ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xl font-bold tracking-tight">{{ number_format($stats['total'] ?? 0) }}</p>
                    <p class="text-[11px] mt-0.5 truncate {{ $isAllActive ? 'text-slate-300' : 'text-slate-400' }}">Seluruh
                        berkas</p>
                </div>
            </a>

            {{-- Card 2: Menunggu Atasan --}}
            @php
                $isAtasanActive = request('status') === \App\Enums\StatusPermohonan::PENDING_ATASAN->value;
                $urlAtasan = route(
                    'admin.permohonan.index',
                    array_filter([
                        'status' => \App\Enums\StatusPermohonan::PENDING_ATASAN->value,
                        'search' => request('search'),
                        'kantor_id' => request('kantor_id'),
                    ]),
                );
            @endphp
            <a href="{{ $urlAtasan }}"
                class="group p-3.5 rounded-2xl border transition-all duration-200 {{ $isAtasanActive ? 'bg-amber-500 text-white border-amber-500 shadow-md ring-2 ring-amber-500/20' : 'bg-white hover:bg-amber-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-amber-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ $isAtasanActive ? 'text-amber-100' : 'text-amber-700' }}">Atasan</span>
                    <div
                        class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isAtasanActive ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-600' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xl font-bold tracking-tight {{ $isAtasanActive ? 'text-white' : 'text-amber-900' }}">
                        {{ number_format($stats['pending_atasan'] ?? 0) }}
                    </p>
                    <p class="text-[11px] mt-0.5 truncate {{ $isAtasanActive ? 'text-amber-100' : 'text-slate-400' }}">
                        Menunggu atasan</p>
                </div>
            </a>

            {{-- Card 3: Menunggu Direktur --}}
            @php
                $isDirutActive = request('status') === \App\Enums\StatusPermohonan::PENDING_DIRUT->value;
                $urlDirut = route(
                    'admin.permohonan.index',
                    array_filter([
                        'status' => \App\Enums\StatusPermohonan::PENDING_DIRUT->value,
                        'search' => request('search'),
                        'kantor_id' => request('kantor_id'),
                    ]),
                );
            @endphp
            <a href="{{ $urlDirut }}"
                class="group p-3.5 rounded-2xl border transition-all duration-200 {{ $isDirutActive ? 'bg-blue-600 text-white border-blue-600 shadow-md ring-2 ring-blue-600/20' : 'bg-white hover:bg-blue-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-blue-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ $isDirutActive ? 'text-blue-100' : 'text-blue-700' }}">Direktur</span>
                    <div
                        class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isDirutActive ? 'bg-blue-700 text-white' : 'bg-blue-50 text-blue-600' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xl font-bold tracking-tight {{ $isDirutActive ? 'text-white' : 'text-blue-900' }}">
                        {{ number_format($stats['pending_dirut'] ?? 0) }}
                    </p>
                    <p class="text-[11px] mt-0.5 truncate {{ $isDirutActive ? 'text-blue-100' : 'text-slate-400' }}">
                        Menunggu direksi</p>
                </div>
            </a>

            {{-- Card 4: Menunggu IT --}}
            @php
                $isItActive = request('status') === \App\Enums\StatusPermohonan::PENDING_IT->value;
                $urlIt = route(
                    'admin.permohonan.index',
                    array_filter([
                        'status' => \App\Enums\StatusPermohonan::PENDING_IT->value,
                        'search' => request('search'),
                        'kantor_id' => request('kantor_id'),
                    ]),
                );
            @endphp
            <a href="{{ $urlIt }}"
                class="group p-3.5 rounded-2xl border transition-all duration-200 {{ $isItActive ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-600/20' : 'bg-white hover:bg-indigo-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-indigo-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ $isItActive ? 'text-indigo-100' : 'text-indigo-700' }}">Eksekusi
                        IT</span>
                    <div
                        class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isItActive ? 'bg-indigo-700 text-white' : 'bg-indigo-50 text-indigo-600' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xl font-bold tracking-tight {{ $isItActive ? 'text-white' : 'text-indigo-900' }}">
                        {{ number_format($stats['pending_it'] ?? 0) }}
                    </p>
                    <p class="text-[11px] mt-0.5 truncate {{ $isItActive ? 'text-indigo-100' : 'text-slate-400' }}">Antrean
                        IT</p>
                </div>
            </a>

            {{-- Card 5: Selesai --}}
            @php
                $isExecutedActive = request('status') === \App\Enums\StatusPermohonan::EXECUTED->value;
                $urlExecuted = route(
                    'admin.permohonan.index',
                    array_filter([
                        'status' => \App\Enums\StatusPermohonan::EXECUTED->value,
                        'search' => request('search'),
                        'kantor_id' => request('kantor_id'),
                    ]),
                );
            @endphp
            <a href="{{ $urlExecuted }}"
                class="group p-3.5 rounded-2xl border transition-all duration-200 {{ $isExecutedActive ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-600/20' : 'bg-white hover:bg-emerald-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-emerald-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ $isExecutedActive ? 'text-emerald-100' : 'text-emerald-700' }}">Selesai</span>
                    <div
                        class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isExecutedActive ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p
                        class="text-xl font-bold tracking-tight {{ $isExecutedActive ? 'text-white' : 'text-emerald-900' }}">
                        {{ number_format($stats['executed'] ?? 0) }}
                    </p>
                    <p class="text-[11px] mt-0.5 truncate {{ $isExecutedActive ? 'text-emerald-100' : 'text-slate-400' }}">
                        Telah aktif</p>
                </div>
            </a>

            {{-- Card 6: Ditolak --}}
            @php
                $isRejectedActive = request('status') === \App\Enums\StatusPermohonan::REJECTED->value;
                $urlRejected = route(
                    'admin.permohonan.index',
                    array_filter([
                        'status' => \App\Enums\StatusPermohonan::REJECTED->value,
                        'search' => request('search'),
                        'kantor_id' => request('kantor_id'),
                    ]),
                );
            @endphp
            <a href="{{ $urlRejected }}"
                class="group p-3.5 rounded-2xl border transition-all duration-200 {{ $isRejectedActive ? 'bg-rose-600 text-white border-rose-600 shadow-md ring-2 ring-rose-600/20' : 'bg-white hover:bg-rose-50/50 text-slate-800 border-slate-200/80 shadow-2xs hover:border-rose-200' }}">
                <div class="flex items-center justify-between gap-2">
                    <span
                        class="text-[11px] font-bold uppercase tracking-wider {{ $isRejectedActive ? 'text-rose-100' : 'text-rose-700' }}">Ditolak</span>
                    <div
                        class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isRejectedActive ? 'bg-rose-700 text-white' : 'bg-rose-50 text-rose-600' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xl font-bold tracking-tight {{ $isRejectedActive ? 'text-white' : 'text-rose-900' }}">
                        {{ number_format($stats['rejected'] ?? 0) }}
                    </p>
                    <p class="text-[11px] mt-0.5 truncate {{ $isRejectedActive ? 'text-rose-100' : 'text-slate-400' }}">
                        Ditolak / batal</p>
                </div>
            </a>
        </div>

        {{-- ── 3. Filter & Search Control Panel ── --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm animate-fade-up"
            style="animation-delay: 0.1s;">
            <form method="GET" action="{{ route('admin.permohonan.index') }}" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    {{-- Input Pencarian --}}
                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Pencarian Dokumen
                        </label>
                        <div class="relative">
                            <input name="search" type="text" value="{{ request('search') }}"
                                class="input w-full pl-9 pr-3 rounded-xl border-slate-200 focus:bg-white text-sm"
                                placeholder="Nomor dokumen, nama pemohon, NIK...">
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

                    {{-- Dropdown Status --}}
                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Status Alur
                        </label>
                        <div class="relative">
                            <select name="status"
                                class="input w-full appearance-none pr-9 rounded-xl border-slate-200 focus:bg-white text-sm cursor-pointer">
                                <option value="">Semua Status</option>
                                @foreach ($statuses as $s)
                                    <option value="{{ $s->value }}" @selected(request('status') === $s->value)>
                                        {{ $s->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Dropdown Kantor --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">
                            Kantor Cabang
                        </label>
                        <div class="relative">
                            <select name="kantor_id"
                                class="input w-full appearance-none pr-9 rounded-xl border-slate-200 focus:bg-white text-sm cursor-pointer">
                                <option value="">Semua Kantor</option>
                                @foreach ($kantor as $k)
                                    <option value="{{ $k->id }}" @selected(request('kantor_id') == $k->id)>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Filter --}}
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

                        @if (request()->hasAny(['search', 'status', 'kantor_id']) &&
                                (request('search') || request('status') || request('kantor_id')))
                            <a href="{{ route('admin.permohonan.index') }}"
                                class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300"
                                title="Reset semua filter">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Active Filter Chips --}}
                @if (request()->hasAny(['search', 'status', 'kantor_id']) &&
                        (request('search') || request('status') || request('kantor_id')))
                    <div class="flex items-center gap-2 flex-wrap pt-2 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">Filter terpasang:</span>

                        @if (request('search'))
                            <a href="{{ route('admin.permohonan.index', array_filter(['status' => request('status'), 'kantor_id' => request('kantor_id')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                <span>Pencarian: <strong>"{{ request('search') }}"</strong></span>
                                <span class="text-slate-400 font-bold">&times;</span>
                            </a>
                        @endif

                        @if (request('status'))
                            @php
                                $statusObj = \App\Enums\StatusPermohonan::tryFrom(request('status'));
                            @endphp
                            <a href="{{ route('admin.permohonan.index', array_filter(['search' => request('search'), 'kantor_id' => request('kantor_id')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 transition-colors">
                                <span>Status:
                                    <strong>{{ $statusObj ? $statusObj->label() : request('status') }}</strong></span>
                                <span class="text-amber-500 font-bold">&times;</span>
                            </a>
                        @endif

                        @if (request('kantor_id'))
                            @php
                                $kantorObj = $kantor->firstWhere('id', request('kantor_id'));
                            @endphp
                            <a href="{{ route('admin.permohonan.index', array_filter(['search' => request('search'), 'status' => request('status')])) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100 transition-colors">
                                <span>Kantor: <strong>{{ $kantorObj?->nama ?? request('kantor_id') }}</strong></span>
                                <span class="text-blue-500 font-bold">&times;</span>
                            </a>
                        @endif

                        <a href="{{ route('admin.permohonan.index') }}"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 ml-1 hover:underline">
                            Hapus Semua
                        </a>
                    </div>
                @endif
            </form>
        </div>

        {{-- ── 4. Main Data Table Container ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.15s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Daftar Dokumen Permohonan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan
                        <strong class="text-slate-700">{{ $permohonan->firstItem() ?? 0 }}</strong> -
                        <strong class="text-slate-700">{{ $permohonan->lastItem() ?? 0 }}</strong> dari
                        <strong class="text-slate-700">{{ number_format($permohonan->total()) }}</strong> permohonan
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        class="text-xs font-mono font-semibold text-slate-500 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                        Halaman {{ $permohonan->currentPage() }} / {{ $permohonan->lastPage() }}
                    </span>
                </div>
            </div>

            @if ($permohonan->isEmpty())
                <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-200/80 text-slate-400 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1">Tidak ada data permohonan</h4>
                    <p class="text-xs text-slate-500 max-w-sm mb-4">
                        @if (request()->hasAny(['search', 'status', 'kantor_id']) &&
                                (request('search') || request('status') || request('kantor_id')))
                            Tidak ada permohonan yang sesuai dengan filter pencarian yang diterapkan.
                        @else
                            Belum ada permohonan FRUID yang diajukan dalam sistem.
                        @endif
                    </p>
                    @if (request()->hasAny(['search', 'status', 'kantor_id']) &&
                            (request('search') || request('status') || request('kantor_id')))
                        <a href="{{ route('admin.permohonan.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter Pencarian
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-4 sm:px-6">Dokumen & Form</th>
                                <th class="py-3.5 px-4">Pemohon</th>
                                <th class="py-3.5 px-4">Kantor & Jabatan</th>
                                <th class="py-3.5 px-4">Jenis & Akses</th>
                                <th class="py-3.5 px-4">Status Alur</th>
                                <th class="py-3.5 px-4">Tanggal Pengajuan</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($permohonan as $item)
                                @php
                                    $isRangkap = $item->form_type->value === 'rangkap';
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    {{-- Dokumen & Form --}}
                                    <td class="py-3.5 px-4 sm:px-6 align-middle">
                                        <div class="space-y-1">
                                            <span
                                                class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block shadow-2xs">
                                                {{ $item->nomor_dokumen ?? '— Draft —' }}
                                            </span>
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                @if ($isRangkap)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                        Rangkap Jabatan
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                                        Reguler
                                                    </span>
                                                @endif

                                                @if ($item->revision_count > 0)
                                                    <span
                                                        class="text-[10px] font-semibold text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded border border-purple-200">
                                                        Rev.{{ $item->revision_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Pemohon & NIK --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs flex-shrink-0">
                                                {{ strtoupper(substr($item->pemohon?->name ?? ($item->nama_pemohon ?? 'P'), 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 truncate">
                                                    {{ $item->pemohon?->name ?? ($item->nama_pemohon ?? '—') }}
                                                </p>
                                                <p class="font-mono text-[11px] text-slate-500 mt-0.5">
                                                    {{ $item->nik_pemohon ?? ($item->pemohon?->nik ?? '—') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kantor & Jabatan --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <div>
                                            <p class="font-semibold text-slate-800 truncate flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span>{{ $item->kantor?->nama ?? '—' }}</span>
                                            </p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                                                {{ $item->pemohon?->jabatan_label ?? ($item->jabatan_pemohon ?? '—') }}
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Jenis & Akses USSI --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="space-y-1">
                                            <span
                                                class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                                {{ $item->jenis_permohonan->label() }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-slate-400">USSI:</span>
                                                <span
                                                    class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-1.5 py-0.2 rounded">
                                                    {{ $item->access_level?->value ?? 'USER' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Status Alur --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="space-y-1">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $item->status->badgeClass() }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
                                                {{ $item->status->label() }}
                                            </span>
                                            @if ($item->executor || $item->nama_executor)
                                                <p class="text-[10px] text-slate-500 font-medium">
                                                    IT: <span
                                                        class="text-slate-700 font-semibold">{{ $item->executor?->name ?? $item->nama_executor }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Tanggal Pengajuan --}}
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="space-y-0.5">
                                            <p class="font-medium text-slate-700">
                                                {{ $item->created_at->locale('id')->isoFormat('D MMM Y') }}
                                            </p>
                                            <p class="font-mono text-[11px] text-slate-400">
                                                {{ $item->created_at->format('H:i') }} WIB
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-3.5 px-4 sm:px-6 align-middle text-right">
                                        <a href="{{ route('admin.permohonan.show', $item) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:text-brand-600 hover:border-brand-300 hover:bg-brand-50/50 shadow-2xs transition-all active:scale-95"
                                            title="Buka rincian lengkap permohonan">
                                            <span>Detail</span>
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Nav --}}
                @if ($permohonan->hasPages())
                    <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $permohonan->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
