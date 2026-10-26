@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
    <div class="space-y-6">

        {{-- ── 1. Page Header & Quick Actions ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-brand-50 rounded-xl border border-brand-100 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Manajemen Pengguna & Akses</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Kelola data karyawan, penetapan hak akses role, dan status akun sistem
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
                <a href="{{ route('admin.users.pending') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-white text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 shadow-sm hover:bg-slate-50 hover:text-amber-600 hover:border-amber-200 transition-all active:scale-95">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Pending OTP</span>
                    @if ($kpi['pending_verifikasi'] > 0)
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white bg-amber-500 rounded-full leading-none">
                            {{ $kpi['pending_verifikasi'] > 99 ? '99+' : $kpi['pending_verifikasi'] }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.sessions.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 bg-white text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 shadow-sm hover:bg-slate-50 hover:text-brand-600 hover:border-brand-200 transition-all active:scale-95">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Session Monitor</span>
                </a>

                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm shadow-brand-500/25 hover:bg-brand-700 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah User</span>
                </a>
            </div>
        </div>

        {{-- ── 2. Metric KPI Summary Bar ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 animate-fade-up" style="animation-delay: 0.05s;">
            {{-- Total User --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total User</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5">{{ $kpi['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            {{-- User Aktif --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Aktif</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5">{{ $kpi['aktif'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- User Online --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <p class="text-[11px] font-bold text-brand-600 uppercase tracking-wider">Online</p>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5">{{ $kpi['online'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                </div>
            </div>

            {{-- Pending OTP --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Pending OTP</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5">{{ $kpi['pending_verifikasi'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Suspended --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-red-600 uppercase tracking-wider">Suspended</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5">{{ $kpi['suspended'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>

            {{-- Locked --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-orange-600 uppercase tracking-wider">Locked</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 mt-0.5">{{ $kpi['locked'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── 3. Filter Bar ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5 animate-fade-up" style="animation-delay: 0.1s;">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5 items-end">
                {{-- Pencarian --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input name="search" type="text" value="{{ request('search') }}"
                               class="input pl-9 w-full"
                               placeholder="Cari nama, NIK, atau email...">
                    </div>
                </div>

                {{-- Filter Kantor --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kantor</label>
                    <select name="kantor_id" class="input w-full">
                        <option value="">Semua Kantor</option>
                        @foreach ($kantor as $k)
                            <option value="{{ $k->id }}" @selected(request('kantor_id') == $k->id)>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Role --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Role</label>
                    <select name="role" class="input w-full">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->value }}" @selected(request('role') === $r->value)>{{ $r->label() }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Status Akun</label>
                    <select name="status" class="input w-full">
                        <option value="">Semua Status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                        <option value="locked" @selected(request('status') === 'locked')>Locked</option>
                        <option value="pending_verifikasi" @selected(request('status') === 'pending_verifikasi')>Pending OTP</option>
                    </select>
                </div>

                {{-- Action Buttons Filter --}}
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-800 text-white hover:bg-slate-900 transition-colors shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if (request()->hasAny(['search', 'kantor_id', 'role', 'status', 'online']))
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center justify-center px-3 py-2.5 rounded-xl font-semibold text-xs text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors"
                           title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── 4. Tabel Pengguna Enterprise ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden animate-fade-up" style="animation-delay: 0.15s;">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Daftar Pengguna Sistem</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Menampilkan seluruh pengguna yang terdaftar di eFRUID</p>
                </div>
                <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ $users->total() }} Pengguna
                </span>
            </div>

            @if ($users->isEmpty())
                {{-- Empty State --}}
                <div class="px-6 py-16 flex flex-col items-center justify-center text-center bg-white">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Pengguna Ditemukan</h4>
                    <p class="text-sm text-slate-500 max-w-sm">
                        Coba sesuaikan kata kunci pencarian atau ubah filter untuk menemukan pengguna yang Anda cari.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto custom-scrollbar w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                                <th class="px-5 sm:px-6 py-3.5">Pengguna</th>
                                <th class="px-5 sm:px-6 py-3.5">Kantor & Jabatan</th>
                                <th class="px-5 sm:px-6 py-3.5">Role</th>
                                <th class="px-5 sm:px-6 py-3.5">Status Akun</th>
                                <th class="px-5 sm:px-6 py-3.5">Login Terakhir</th>
                                <th class="px-5 sm:px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @foreach ($users as $u)
                                @php
                                    $isOnline = in_array($u->id, $onlineIds);
                                    $status = $u->accountStatus();
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    {{-- Kolom Pengguna --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar dengan Online Dot --}}
                                            <div class="relative flex-shrink-0">
                                                <div class="w-9 h-9 rounded-xl bg-brand-100 border border-brand-200 text-brand-700 font-bold text-xs flex items-center justify-center">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </div>
                                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-white {{ $isOnline ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                                      title="{{ $isOnline ? 'Online' : 'Offline' }}"></span>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.users.show', $u) }}"
                                                       class="font-bold text-slate-900 group-hover:text-brand-600 transition-colors truncate">
                                                        {{ $u->name }}
                                                    </a>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5 font-mono">
                                                    <span class="bg-slate-100 text-slate-700 px-1.5 py-0.2 rounded text-[11px] font-bold">{{ $u->nik }}</span>
                                                    <span class="text-slate-300">&middot;</span>
                                                    <span class="truncate text-slate-400 font-sans">{{ $u->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Kantor & Jabatan --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="font-medium text-slate-800 text-xs sm:text-sm">{{ $u->kantor?->nama ?? '—' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $u->jabatan_label }}</div>
                                    </td>

                                    {{-- Kolom Role --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($u->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                                                    {{ $role->label }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">Tanpa Role</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    {{-- Kolom Status Akun --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        @if ($status === 'ACTIVE')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @elseif($status === 'SUSPENDED')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Suspended
                                            </span>
                                        @elseif($status === 'LOCKED')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                                Locked
                                            </span>
                                        @elseif($status === 'PENDING_VERIFICATION')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Pending OTP
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif

                                        @if (!$u->email_verified && $u->is_active)
                                            <span class="block text-[10px] font-semibold text-amber-600 mt-1">Belum Verifikasi</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Login Terakhir --}}
                                    <td class="px-5 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs font-medium text-slate-700">
                                            {{ $u->last_login_at ? $u->last_login_at->locale('id')->diffForHumans() : 'Belum pernah' }}
                                        </div>
                                        @if ($u->last_login_ip)
                                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">{{ $u->last_login_ip }}</div>
                                        @endif
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="px-5 sm:px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.users.show', $u) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors shadow-xs">
                                                <span>Detail</span>
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $u) }}"
                                               class="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors"
                                               title="Edit Pengguna">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if ($users->hasPages())
                    <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $users->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
