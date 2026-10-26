@extends('layouts.app')

@section('title', 'Detail Pengguna — ' . $user->name)
@section('page-title', 'Detail User')

@section('content')
    <div class="space-y-6" x-data="{ tab: 'profile' }">

        {{-- ── 1. Executive Profile Header Card ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-7 animate-fade-up">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                {{-- Left: Avatar & Identity --}}
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white font-bold text-2xl sm:text-3xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @if ($user->last_login_at && $user->last_login_at >= now()->subMinutes(5))
                            <span
                                class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white ring-2 ring-emerald-100"
                                title="Online"></span>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-lg sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                            {{-- Role Badge --}}
                            @foreach ($user->roles as $role)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                                    {{ $role->label }}
                                </span>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-500">
                            <span
                                class="font-mono font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">
                                {{ $user->nik }}
                            </span>
                            <span>{{ $user->email }}</span>
                            <span class="text-slate-300">&middot;</span>
                            <span>{{ $user->kantor?->nama ?? '—' }} ({{ $user->jabatan_label }})</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Status Badges & Quick Nav --}}
                <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
                    @php $status = $user->accountStatus(); @endphp
                    @if ($status === 'ACTIVE')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @elseif($status === 'SUSPENDED')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            Suspended
                        </span>
                    @elseif($status === 'LOCKED')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            Locked
                        </span>
                    @elseif($status === 'PENDING_VERIFICATION')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Pending OTP
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            Nonaktif
                        </span>
                    @endif

                    @if ($user->email_verified)
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Email Verified
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                            Belum Verifikasi
                        </span>
                    @endif

                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-brand-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs hover:bg-brand-700 transition-colors active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit User</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white text-slate-600 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 hover:bg-slate-50 transition-colors active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            {{-- ── 2. Segmented Navigation Tabs ── --}}
            <div class="flex items-center gap-1.5 mt-6 pt-4 border-t border-slate-100 overflow-x-auto custom-scrollbar">
                @php
                    $tabItems = [
                        'profile' => [
                            'label' => 'Profil & Informasi',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        ],
                        'sessions' => [
                            'label' => 'Sessions Aktif',
                            'icon' =>
                                'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                        ],
                        'activity' => [
                            'label' => 'Aktivitas Log',
                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        'permohonan' => [
                            'label' => 'Riwayat Permohonan',
                            'icon' =>
                                'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        ],
                        'security' => [
                            'label' => 'Keamanan Akun',
                            'icon' =>
                                'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                        ],
                    ];
                @endphp

                @foreach ($tabItems as $key => $item)
                    <button @click="tab = '{{ $key }}'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all whitespace-nowrap focus:outline-none"
                        :class="tab === '{{ $key }}'
                            ?
                            'bg-brand-50 text-brand-700 border border-brand-200/80 shadow-xs' :
                            'text-slate-500 hover:text-slate-800 hover:bg-slate-50'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        <span>{{ $item['label'] }}</span>
                        @if ($key === 'sessions' && $sessions->count() > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-brand-200/80 text-brand-800">
                                {{ $sessions->count() }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
         TAB 1: PROFIL & INFORMASI
    ══════════════════════════════════════════════════ --}}
        <div x-show="tab === 'profile'" class="space-y-6 animate-fade-up">
            {{-- Card Info Akun --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Lengkap Akun
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kantor</p>
                        <p class="font-semibold text-slate-800 mt-1">
                            {{ $user->kantor?->label ?? ($user->kantor?->nama ?? '—') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jabatan</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $user->jabatan_label }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Role Terdaftar</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach ($user->roles as $role)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $role->label }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Login Terakhir</p>
                        <p class="font-medium text-slate-800 mt-1">
                            {{ $user->last_login_at ? $user->last_login_at->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB' : 'Belum pernah login' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">IP Address Terakhir</p>
                        <p class="font-mono text-slate-700 mt-1 text-xs">{{ $user->last_login_ip ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terdaftar Sejak</p>
                        <p class="font-medium text-slate-800 mt-1">
                            {{ $user->created_at->locale('id')->isoFormat('D MMMM Y') }}
                        </p>
                    </div>

                    @if ($user->isSuspended())
                        <div class="sm:col-span-2 lg:col-span-3 p-3.5 bg-red-50 rounded-xl border border-red-200">
                            <p class="text-xs font-bold text-red-700 uppercase tracking-wider">Alasan Penangguhan
                                (Suspension Reason)</p>
                            <p class="text-sm font-medium text-red-900 mt-1">{{ $user->suspension_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Aksi Admin --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Tindakan Otorisasi Admin
                </h3>

                <div class="flex flex-wrap gap-3">
                    {{-- Verifikasi Manual --}}
                    @if (!$user->email_verified && $user->is_active)
                        <div x-data="{ open: false }">
                            <button @click="open = true"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-xs hover:bg-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verifikasi Manual
                            </button>
                            <template x-teleport="body">
                                <div x-show="open"
                                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden"
                                    x-cloak>
                                    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm" @click="open = false">
                                    </div>
                                    <div
                                        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200">
                                        <h3 class="text-base font-bold text-slate-900 mb-1">Verifikasi Akun Manual</h3>
                                        <p class="text-xs text-slate-500 mb-4">Pengguna:
                                            <strong>{{ $user->name }}</strong></p>
                                        <form method="POST" action="{{ route('admin.users.manualVerify', $user) }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="label label-required">Alasan Verifikasi Manual</label>
                                                <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                    placeholder="Misal: Nomor HP/Email terkonfirmasi langsung oleh HRD..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="open = false"
                                                    class="btn-ghost btn-sm">Batal</button>
                                                <button type="submit" class="btn-primary btn-sm">Verifikasi
                                                    Sekarang</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif

                    {{-- Suspend / Unsuspend --}}
                    @if (!$user->isSuperAdmin())
                        @if ($user->isSuspended())
                            <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}"
                                onsubmit="return confirm('Cabut suspend akun ini?')">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 hover:bg-slate-200 transition-colors">
                                    Cabut Status Suspend
                                </button>
                            </form>
                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-700 text-xs font-bold rounded-xl border border-red-200 hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Suspend Akun
                                </button>
                                <template x-teleport="body">
                                    <div x-show="open"
                                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden"
                                        x-cloak>
                                        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm" @click="open = false">
                                        </div>
                                        <div
                                            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200">
                                            <h3 class="text-base font-bold text-red-700 mb-1">Suspend Akun Pengguna</h3>
                                            <p class="text-xs text-slate-500 mb-4">Pengguna
                                                <strong>{{ $user->name }}</strong> tidak akan dapat mengakses sistem.</p>
                                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="label label-required">Alasan Penangguhan</label>
                                                    <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                        placeholder="Jelaskan alasan penangguhan akun..."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="open = false"
                                                        class="btn-ghost btn-sm">Batal</button>
                                                    <button type="submit" class="btn-danger btn-sm">Konfirmasi
                                                        Suspend</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endif

                        {{-- Lock / Unlock --}}
                        @if ($user->isLocked())
                            <form method="POST" action="{{ route('admin.users.unlock', $user) }}"
                                onsubmit="return confirm('Buka kunci akun ini?')">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                    Buka Kunci Akun
                                </button>
                            </form>
                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-50 text-orange-700 text-xs font-bold rounded-xl border border-orange-200 hover:bg-orange-100 transition-colors">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Kunci Akun
                                </button>
                                <template x-teleport="body">
                                    <div x-show="open"
                                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden"
                                        x-cloak>
                                        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm" @click="open = false">
                                        </div>
                                        <div
                                            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200">
                                            <h3 class="text-base font-bold text-slate-900 mb-1">Kunci Akun Pengguna</h3>
                                            <p class="text-xs text-slate-500 mb-4">Kunci akun
                                                <strong>{{ $user->name }}</strong> untuk mencegah login sementara.</p>
                                            <form method="POST" action="{{ route('admin.users.lock', $user) }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="label label-required">Alasan Penguncian</label>
                                                    <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                        placeholder="Jelaskan alasan penguncian akun..."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="open = false"
                                                        class="btn-ghost btn-sm">Batal</button>
                                                    <button type="submit" class="btn-danger btn-sm">Kunci Akun</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Card Reset Password --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Reset Password Pengguna
                </h3>

                <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}" x-data="{ loading: false }"
                    @submit="loading = true">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label label-required">Password Baru</label>
                            <input name="password" type="password" class="input @error('password') input-error @enderror"
                                placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="label label-required">Konfirmasi Password</label>
                            <input name="password_confirmation" type="password" class="input"
                                placeholder="Ketik ulang password baru" required>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs bg-red-600 text-white hover:bg-red-700 transition-colors shadow-xs"
                            :disabled="loading">
                            <span x-text="loading ? 'Mereset Password...' : 'Reset Password Pengguna'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
         TAB 2: SESSIONS AKTIF
    ══════════════════════════════════════════════════ --}}
        <div x-show="tab === 'sessions'" class="space-y-6 animate-fade-up">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Sesi Perangkat Aktif</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Total {{ $sessions->count() }} sesi perangkat tercatat
                        </p>
                    </div>

                    @if ($sessions->count() > 0)
                        <div x-data="{ open: false }">
                            <button @click="open = true"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg border border-red-200 hover:bg-red-100 transition-colors">
                                Logout Semua Sesi
                            </button>
                            <template x-teleport="body">
                                <div x-show="open"
                                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden"
                                    x-cloak>
                                    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm" @click="open = false">
                                    </div>
                                    <div
                                        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200">
                                        <h3 class="text-base font-bold text-red-700 mb-1">Force Logout Semua Sesi</h3>
                                        <p class="text-xs text-slate-600 mb-4">Seluruh {{ $sessions->count() }} sesi aktif
                                            dari pengguna <strong>{{ $user->name }}</strong> akan diterminasi.</p>
                                        <form method="POST" action="{{ route('admin.users.forceLogoutAll', $user) }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="label label-required">Alasan Terminasi</label>
                                                <textarea name="reason" rows="2" required class="input w-full resize-none"
                                                    placeholder="Tuliskan alasan force logout..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="open = false"
                                                    class="btn-ghost btn-sm">Batal</button>
                                                <button type="submit" class="btn-danger btn-sm">Logout Semua
                                                    Sesi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif
                </div>

                @if ($sessions->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-sm text-slate-500">Tidak ada sesi perangkat yang sedang aktif saat ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto custom-scrollbar w-full">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr
                                    class="bg-slate-50/80 border-b border-slate-100 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                                    <th class="px-5 sm:px-6 py-3.5">Status</th>
                                    <th class="px-5 sm:px-6 py-3.5">IP Address</th>
                                    <th class="px-5 sm:px-6 py-3.5">Perangkat / User Agent</th>
                                    <th class="px-5 sm:px-6 py-3.5">Aktivitas Terakhir</th>
                                    <th class="px-5 sm:px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach ($sessions as $sess)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 sm:px-6 py-4">
                                            @if ($sess->is_online)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                                                </span>
                                            @elseif($sess->is_idle)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Idle
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                    Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 font-mono text-xs font-bold text-slate-700">
                                            {{ $sess->ip_address ?? '—' }}</td>
                                        <td class="px-5 sm:px-6 py-4 text-xs text-slate-500 max-w-xs truncate"
                                            title="{{ $sess->user_agent }}">
                                            {{ $sess->user_agent ? Str::limit($sess->user_agent, 65) : '—' }}
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 text-xs text-slate-500">
                                            {{ $sess->last_active->locale('id')->diffForHumans() }}
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 text-right">
                                            <div x-data="{ open: false }">
                                                <button @click="open = true"
                                                    class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:text-red-800 hover:bg-red-50 px-2.5 py-1 rounded-lg transition-colors">
                                                    Force Logout
                                                </button>
                                                <template x-teleport="body">
                                                    <div x-show="open"
                                                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-hidden"
                                                        x-cloak>
                                                        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"
                                                            @click="open = false"></div>
                                                        <div
                                                            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200 text-left">
                                                            <h3 class="text-base font-bold text-slate-900 mb-1">Terminasi
                                                                Sesi Pengguna</h3>
                                                            <p class="text-xs text-slate-500 mb-1">User:
                                                                <strong>{{ $user->name }}</strong></p>
                                                            <p class="text-xs font-mono text-slate-400 mb-4">IP:
                                                                {{ $sess->ip_address }}</p>
                                                            <form method="POST"
                                                                action="{{ route('admin.users.forceLogout', $user) }}">
                                                                @csrf
                                                                <input type="hidden" name="session_id"
                                                                    value="{{ $sess->id }}">
                                                                <div class="mb-4">
                                                                    <label class="label label-required">Alasan
                                                                        Terminasi</label>
                                                                    <textarea name="reason" rows="2" required class="input w-full resize-none"
                                                                        placeholder="Alasan force logout..."></textarea>
                                                                </div>
                                                                <div class="flex justify-end gap-2">
                                                                    <button type="button" @click="open = false"
                                                                        class="btn-ghost btn-sm">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn-danger btn-sm">Terminasi Sesi</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
         TAB 3: AKTIVITAS AUDIT
    ══════════════════════════════════════════════════ --}}
        <div x-show="tab === 'activity'" class="space-y-6 animate-fade-up">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Log Jejak Aktivitas</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Menampilkan 30 aktivitas terbaru yang tercatat di audit
                            trail</p>
                    </div>
                    <a href="{{ route('admin.audit-logs.index', ['user_id' => $user->id]) }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:underline">
                        Lihat Semua di Audit Log &rarr;
                    </a>
                </div>

                @if ($activity->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-sm text-slate-500">Belum ada catatan aktivitas untuk pengguna ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($activity as $log)
                            <div
                                class="px-5 sm:px-6 py-3.5 flex items-start gap-3.5 hover:bg-slate-50/60 transition-colors">
                                <div class="w-2.5 h-2.5 rounded-full bg-brand-500 mt-1.5 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800">{{ $log->aksi->label() }}</p>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                        @if ($log->nomor_dokumen)
                                            <span
                                                class="font-mono font-semibold text-brand-600">{{ $log->nomor_dokumen }}</span>
                                            <span class="text-slate-300">&middot;</span>
                                        @endif
                                        @if ($log->ip_address)
                                            <span class="font-mono text-slate-400">IP: {{ $log->ip_address }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs text-slate-400 whitespace-nowrap flex-shrink-0">
                                    {{ $log->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }} WIB
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
         TAB 4: RIWAYAT PERMOHONAN
    ══════════════════════════════════════════════════ --}}
        <div x-show="tab === 'permohonan'" class="space-y-6 animate-fade-up">
            @php
                use App\Enums\StatusPermohonan;
                $summaryItems = [
                    ['label' => 'Total', 'status' => null],
                    ['label' => 'Draft', 'status' => StatusPermohonan::DRAFT->value],
                    ['label' => 'Pending', 'status' => StatusPermohonan::PENDING_ATASAN->value],
                    ['label' => 'Approved', 'status' => StatusPermohonan::PENDING_IT->value],
                    ['label' => 'Ditolak', 'status' => StatusPermohonan::REJECTED->value],
                    ['label' => 'Executed', 'status' => StatusPermohonan::EXECUTED->value],
                ];
            @endphp

            {{-- Stat Summary Permohonan --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
                @foreach ($summaryItems as $item)
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/70 shadow-sm text-center">
                        <p class="text-xl font-bold text-slate-800">
                            {{ $item['status'] ? $permohonanSummary[$item['status']] ?? 0 : $permohonanSummary->sum() }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                    <h3 class="text-sm font-bold text-slate-800">Daftar Dokumen Permohonan FRUID</h3>
                </div>

                @if ($permohonan->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-sm text-slate-500">Belum ada riwayat permohonan dari pengguna ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto custom-scrollbar w-full">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr
                                    class="bg-slate-50/80 border-b border-slate-100 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                                    <th class="px-5 sm:px-6 py-3.5">Nomor Dokumen</th>
                                    <th class="px-5 sm:px-6 py-3.5">Jenis Permohonan</th>
                                    <th class="px-5 sm:px-6 py-3.5">Status</th>
                                    <th class="px-5 sm:px-6 py-3.5">Tanggal</th>
                                    <th class="px-5 sm:px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach ($permohonan as $p)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 sm:px-6 py-4 font-mono font-bold text-xs text-slate-800">
                                            {{ $p->nomor_dokumen ?? '— Draft —' }}
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 font-semibold text-slate-800">
                                            {{ $p->jenis_permohonan->label() }}</td>
                                        <td class="px-5 sm:px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $p->status->badgeClass() }}">
                                                {{ $p->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 text-xs text-slate-500">
                                            {{ $p->created_at->locale('id')->isoFormat('D MMM Y') }}
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 text-right">
                                            <a href="{{ route('admin.permohonan.show', $p) }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($permohonan->hasPages())
                        <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $permohonan->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
         TAB 5: KEAMANAN AKUN
    ══════════════════════════════════════════════════ --}}
        <div x-show="tab === 'security'" class="space-y-6 animate-fade-up">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Status Metrik Keamanan
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Akun</p>
                        <p
                            class="font-bold text-base mt-1 {{ $user->accountStatus() === 'ACTIVE' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $user->accountStatus() }}
                        </p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gagal Login</p>
                        <p
                            class="font-bold text-base mt-1 {{ $user->failed_login_count > 0 ? 'text-amber-600' : 'text-slate-800' }}">
                            {{ $user->failed_login_count }} kali
                        </p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dikunci Sejak</p>
                        <p class="font-medium text-slate-800 mt-1">
                            {{ $user->locked_at ? $user->locked_at->locale('id')->isoFormat('D MMM Y, HH:mm') : 'Tidak Terkunci' }}
                        </p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Disuspend Sejak</p>
                        <p class="font-medium text-slate-800 mt-1">
                            {{ $user->suspended_at ? $user->suspended_at->locale('id')->isoFormat('D MMM Y, HH:mm') : 'Tidak Disuspend' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                    <h3 class="text-sm font-bold text-slate-800">Security Audit Logs</h3>
                </div>

                @if ($securityLog->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-sm text-slate-500">Tidak ada riwayat insiden keamanan untuk akun ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($securityLog as $log)
                            @php
                                $isFail =
                                    str_contains($log->aksi->value, 'failed') ||
                                    str_contains($log->aksi->value, 'locked') ||
                                    str_contains($log->aksi->value, 'suspended');
                            @endphp
                            <div
                                class="px-5 sm:px-6 py-3.5 flex items-start gap-3.5 hover:bg-slate-50/60 transition-colors">
                                <div
                                    class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0 {{ $isFail ? 'bg-red-500' : 'bg-emerald-500' }}">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-800">{{ $log->aksi->label() }}</p>
                                    @if ($log->after && isset($log->after['reason']))
                                        <p class="text-xs text-slate-600 mt-0.5">Alasan: {{ $log->after['reason'] }}</p>
                                    @endif
                                    <p class="text-xs text-slate-400 font-mono mt-0.5">IP: {{ $log->ip_address }}</p>
                                </div>
                                <span class="text-xs text-slate-400 whitespace-nowrap">
                                    {{ $log->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
