@extends('layouts.app')

@section('title', 'Eksekusi IT')
@section('page-title', 'Antrean Eksekusi IT')

@section('content')
    <div class="space-y-6">

        {{-- ── 1. Page Header & Quick Navigation ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span
                        class="w-8 h-8 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    Antrean Eksekusi IT
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Daftar permohonan FRUID yang telah disetujui dan siap dieksekusi di sistem Core Banking USSI.
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('eksekusi.riwayat') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 rounded-xl shadow-xs transition-all hover:border-slate-300">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Eksekusi Saya
                </a>
            </div>
        </div>

        {{-- ── 2. Metric Highlight Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade-up" style="animation-delay: 0.05s;">
            {{-- Total Menunggu --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Menunggu Eksekusi</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1.5">{{ $pendingCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Permohonan siap dieksekusi</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
            </div>

            {{-- Sedang Saya Kerjakan --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-brand-600 uppercase tracking-wider">Sedang Saya Kerjakan</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-700 mt-1.5">{{ $myClaimedCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Klaim aktif oleh akun anda</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 flex-shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-brand-500 to-brand-700"></div>
            </div>

            {{-- Tersedia untuk Diambil --}}
            @php
                $unclaimedCount = max(0, $pendingCount - $pending->whereNotNull('executor_id')->count());
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Tersedia untuk Diambil</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-1.5">{{ $unclaimedCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Belum diklaim staf IT mana pun</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            </div>
        </div>

        {{-- ── 3. Filter Section ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5 animate-fade-up"
            style="animation-delay: 0.1s;">
            <form method="GET" action="{{ route('eksekusi.index') }}" class="flex flex-col sm:flex-row items-end gap-3.5">
                {{-- Filter Kantor --}}
                <div class="w-full sm:w-64">
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Kantor Cabang / Kas
                        </span>
                    </label>
                    <select name="kantor_id"
                        class="input text-xs sm:text-sm py-2 bg-slate-50/50 border-slate-200 focus:bg-white transition-colors">
                        <option value="">Semua Kantor</option>
                        @foreach ($kantors as $kantor)
                            <option value="{{ $kantor->id }}" @selected(request('kantor_id') == $kantor->id)>
                                {{ $kantor->label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jenis Permohonan --}}
                <div class="w-full sm:w-56">
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Jenis Permohonan
                        </span>
                    </label>
                    <select name="jenis"
                        class="input text-xs sm:text-sm py-2 bg-slate-50/50 border-slate-200 focus:bg-white transition-colors">
                        <option value="">Semua Jenis</option>
                        @foreach (\App\Enums\JenisPermohonan::cases() as $j)
                            <option value="{{ $j->value }}" @selected(request('jenis') === $j->value)>
                                {{ $j->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit & Reset Buttons --}}
                <div class="flex items-center gap-2 w-full sm:w-auto pt-1 sm:pt-0">
                    <button type="submit"
                        class="btn-primary text-xs sm:text-sm py-2 px-4 shadow-xs flex-1 sm:flex-initial">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>

                    @if (request()->hasAny(['kantor_id', 'jenis']))
                        <a href="{{ route('eksekusi.index') }}"
                            class="btn-secondary text-xs sm:text-sm py-2 px-3 text-slate-500 hover:text-slate-800">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── 4. Main Table / Queue List ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.15s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Daftar Antrean Permohonan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Klik <strong>"Ambil"</strong> untuk mengklaim tugas, lalu <strong>"Eksekusi"</strong> setelah
                        konfigurasi selesai di USSI.
                    </p>
                </div>

                <span
                    class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                    Menampilkan {{ $pending->count() }} dari {{ $pendingCount }} antrean
                </span>
            </div>

            @if ($pending->isEmpty())
                <div class="p-12 text-center">
                    <div
                        class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-xs">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">Tidak Ada Permohonan Pending</h4>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        Semua permohonan FRUID yang disetujui telah selesai dieksekusi di sistem USSI.
                    </p>
                    @if (request()->hasAny(['kantor_id', 'jenis']))
                        <div class="mt-4">
                            <a href="{{ route('eksekusi.index') }}" class="btn-secondary btn-sm">
                                Reset Filter Pencarian
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-slate-50/80 border-b border-slate-200 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5">Dokumen & Form</th>
                                <th class="px-4 py-3.5">Pemohon</th>
                                <th class="px-4 py-3.5">Kantor Cabang</th>
                                <th class="px-4 py-3.5">Jenis Akses</th>
                                <th class="px-4 py-3.5">Status Klaim</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pending as $item)
                                @php
                                    $myId = auth()->id();
                                    $isMine = $item->isClaimedBy($myId);
                                @endphp
                                <tr class="transition-colors hover:bg-slate-50/80 {{ $isMine ? 'bg-brand-50/30' : '' }}">
                                    {{-- Dokumen & Tipe --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                                {{ $item->nomor_dokumen }}
                                            </span>
                                            @if ($item->form_type->value === 'rangkap')
                                                <span
                                                    class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                    Rangkap
                                                </span>
                                            @else
                                                <span
                                                    class="text-[10px] font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                                                    Reguler
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1 font-medium">
                                            {{ $item->tanggal_permohonan?->locale('id')->isoFormat('D MMM Y') ?? $item->created_at->locale('id')->isoFormat('D MMM Y') }}
                                        </p>
                                    </td>

                                    {{-- Pemohon --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 border border-slate-200">
                                                {{ strtoupper(substr($item->pemohon?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">
                                                    {{ $item->pemohon?->name ?? '—' }}
                                                </p>
                                                <p class="text-[11px] text-slate-400 truncate">
                                                    {{ $item->pemohon?->jabatan_label ?? 'Staff' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kantor --}}
                                    <td class="px-4 py-4 text-xs font-medium text-slate-700">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span class="truncate">{{ $item->kantor?->nama ?? '—' }}</span>
                                        </div>
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                                            {{ $item->jenis_permohonan->label() }}
                                        </span>
                                    </td>

                                    {{-- Status Klaim --}}
                                    <td class="px-4 py-4">
                                        @if (!$item->isClaimed())
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-2.5 py-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Tersedia
                                            </span>
                                        @elseif ($isMine)
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-700 bg-brand-50 border border-brand-200 rounded-full px-2.5 py-1 shadow-xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-pulse"></span>
                                                Saya Kerjakan
                                            </span>
                                        @else
                                            <div class="space-y-0.5">
                                                <span
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Diambil Rekan
                                                </span>
                                                <p
                                                    class="text-[11px] text-slate-400 font-medium truncate max-w-[130px] pl-1">
                                                    {{ $item->executor?->name }}
                                                </p>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if (!$item->isClaimed())
                                                {{-- Belum diklaim: Tombol Ambil --}}
                                                <form action="{{ route('eksekusi.claim', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-xs transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Ambil
                                                    </button>
                                                </form>
                                            @elseif ($isMine)
                                                {{-- Saya yang ambil: Eksekusi + Lepas --}}
                                                <a href="{{ route('eksekusi.show', $item) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                    Eksekusi
                                                </a>

                                                <form action="{{ route('eksekusi.unclaim', $item) }}" method="POST"
                                                    onsubmit="return confirm('Lepas klaim permohonan ini? Permohonan akan kembali berstatus tersedia untuk tim IT.')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Lepas Klaim">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Diambil orang lain: Hanya Lihat --}}
                                                <a href="{{ route('eksekusi.show', $item) }}"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($pending->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $pending->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
