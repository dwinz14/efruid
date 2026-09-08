@extends('layouts.app')

@section('title', 'Eksekusi Permohonan')
@section('page-title', 'Detail Eksekusi IT')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- ── 1. Top Navigation & Status Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('eksekusi.index') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke antrean eksekusi">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span
                            class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            {{ $permohonan->nomor_dokumen ?? 'Draft Dokumen' }}
                        </span>
                        @if ($permohonan->form_type->value === 'rangkap')
                            <span
                                class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                Rangkap Jabatan
                            </span>
                        @else
                            <span
                                class="text-[10px] font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                                Reguler (Normal)
                            </span>
                        @endif
                        @if ($permohonan->revision_count > 0)
                            <span
                                class="text-[10px] font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                                Revisi ke-{{ $permohonan->revision_count }}
                            </span>
                        @endif
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight mt-1">
                        Eksekusi Permohonan FRUID
                    </h2>
                </div>
            </div>

            <div class="flex-shrink-0">
                <span
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold border shadow-xs {{ $permohonan->status->badgeClass() }}">
                    <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
                    Status: {{ $permohonan->status->label() }}
                </span>
            </div>
        </div>

        {{-- ── 2. Executive Summary Cards ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 animate-fade-up"
            style="animation-delay: 0.05s;">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Pemohon --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemohon</p>
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($permohonan->pemohon?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $permohonan->pemohon?->name ?? '—' }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">{{ $permohonan->pemohon?->jabatan_label ?? 'Staff' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Kantor --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kantor Cabang</p>
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800 mt-1">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span
                            class="truncate">{{ $permohonan->kantor?->label ?? ($permohonan->kantor?->nama ?? '—') }}</span>
                    </div>
                </div>

                {{-- Jenis Permohonan --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jenis Permohonan</p>
                    <div class="mt-1">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $permohonan->jenis_permohonan->label() }}
                        </span>
                    </div>
                </div>

                {{-- Tanggal Pengajuan --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800 mt-1">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMMM Y') ?? $permohonan->created_at->locale('id')->isoFormat('D MMMM Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Verification Records (Stempel Digital Sebelumnya) --}}
            @if (!empty($permohonan->verification_stamps))
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                        Rekam Verifikasi Digital (Verification Stamps)
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach ($permohonan->verification_stamps as $stamp)
                            <div
                                class="flex items-start gap-2.5 p-2.5 bg-slate-50/80 rounded-xl border border-slate-200/60 text-xs">
                                <div
                                    class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate">{{ $stamp['role'] ?? 'Verifikator' }}:
                                        <span class="font-normal">{{ $stamp['nama'] ?? '—' }}</span></p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $stamp['timestamp'] ?? '—' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ── 3. Dokumen FRUID Viewer Frame ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.1s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Lembar Dokumen FRUID Final</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tinjau seluruh rincian hak akses yang akan dikonfigurasi di
                            USSI</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('dokumen.preview', $permohonan) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 border border-brand-200 rounded-lg transition-colors shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Buka Tab Baru
                    </a>
                </div>
            </div>

            {{-- Canvas Viewer --}}
            <div class="bg-slate-100/80 p-3 sm:p-5">
                <div class="w-full bg-white shadow-xl rounded-xl border border-slate-200/80 overflow-hidden"
                    style="height: 740px;">
                    <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                        style="width: 100%; height: 100%; border: none;" title="Preview Dokumen FRUID"
                        sandbox="allow-same-origin allow-scripts allow-popups">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- ── 4. Action Console (Kondisional Berdasarkan Status) ── --}}
        @if ($permohonan->status->value === 'PENDING_IT')
            @php $myId = auth()->id(); @endphp

            @if (!$permohonan->isClaimed())
                {{-- KONDISI A: BELUM DIKLAIM --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 animate-fade-up"
                    style="animation-delay: 0.15s;">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 flex-shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-slate-800">Ambil & Klaim Permohonan Ini</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                                Klik tombol di bawah untuk menandai bahwa anda yang akan mengeksekusi permohonan ini di
                                sistem USSI. Setelah diambil, anggota tim IT lain tidak akan mengeksekusi FRUID yang sama.
                            </p>
                            <form action="{{ route('eksekusi.claim', $permohonan) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm bg-brand-600 text-white hover:bg-brand-700 shadow-md shadow-brand-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/50 active:scale-98">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Ambil & Mulai Kerjakan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif ($permohonan->isClaimedBy($myId))
                {{-- KONDISI B: DIKLAIM OLEH SAYA --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                    style="animation-delay: 0.15s;" x-data="{ confirm: false, loading: false }">

                    {{-- Console Header --}}
                    <div
                        class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Konsol Eksekusi IT</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Diambil sejak
                                    {{ $permohonan->claimed_at?->locale('id')->isoFormat('D MMM Y, HH:mm') ?? 'saat ini' }}
                                    WIB
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('eksekusi.unclaim', $permohonan) }}" method="POST"
                            onsubmit="return confirm('Lepas klaim permohonan ini? Permohonan akan kembali berstatus tersedia untuk tim IT.')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Lepas Klaim
                            </button>
                        </form>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        {{-- Warning jika IT Staff belum punya TTD --}}
                        @if (!auth()->user()->signature_path)
                            <div
                                class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3.5 text-amber-800 text-xs">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="space-y-1">
                                    <span class="font-bold block text-sm">Tanda Tangan Digital Belum Diunggah</span>
                                    <p>Anda belum mengunggah tanda tangan di profil. Tanda tangan anda diperlukan sebagai
                                        Administrator USSI.</p>
                                    <a href="{{ route('profile.edit') }}" target="_blank"
                                        class="inline-flex items-center gap-1 font-bold underline hover:text-amber-900 mt-1">
                                        Upload Tanda Tangan di Profil &rarr;
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Petunjuk Eksekusi --}}
                        <div
                            class="p-4 rounded-xl bg-brand-50/70 border border-brand-200/80 flex items-start gap-3.5 text-brand-900 text-xs">
                            <div
                                class="w-8 h-8 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="font-bold text-sm">Ketentuan Eksekusi USSI</p>
                                <p class="leading-relaxed text-slate-600">
                                    Pastikan konfigurasi User ID atau hak akses permohonan
                                    <strong>{{ $permohonan->nomor_dokumen }}</strong> telah selesai diterapkan di aplikasi
                                    USSI Core Banking. Nama anda (<strong>{{ auth()->user()->name }}</strong>) akan dicatat
                                    sebagai <em>Administrator Aplikasi USSI</em> dan file PDF final akan digenerate
                                    otomatis.
                                </p>
                            </div>
                        </div>

                        {{-- Step 1: Tombol Pembuka Form --}}
                        <div x-show="!confirm">
                            <button type="button" @click="confirm = true"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50 active:scale-98">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Tandai Sudah Selesai Dieksekusi di USSI
                            </button>
                        </div>

                        {{-- Step 2: Form Konfirmasi Eksekusi --}}
                        <div x-show="confirm" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="space-y-4 pt-3 border-t border-slate-100">

                            <form method="POST" action="{{ route('eksekusi.execute', $permohonan) }}"
                                @submit="loading = true">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Catatan Eksekusi IT <span class="text-slate-400 font-normal">(opsional)</span>
                                    </label>
                                    <input name="catatan" type="text" class="input w-full text-xs sm:text-sm"
                                        placeholder="Contoh: User ID telah aktif dengan wewenang Teller di USSI..."
                                        maxlength="255">
                                </div>

                                <div class="flex items-center gap-3 mt-4">
                                    <button type="submit"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-sm bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="loading">
                                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                        </svg>
                                        <span
                                            x-text="loading ? 'Memproses Eksekusi...' : 'Konfirmasi Selesai Eksekusi'"></span>
                                    </button>

                                    <button type="button" @click="confirm = false"
                                        class="btn-secondary py-3 px-4 text-xs font-bold" :disabled="loading">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                {{-- KONDISI C: DIKLAIM REKAN IT LAIN --}}
                <div class="bg-white rounded-2xl border border-amber-200/80 shadow-sm p-5 sm:p-6 animate-fade-up"
                    style="animation-delay: 0.15s;">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 flex-shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-bold text-slate-800">
                                Sedang Dikerjakan oleh {{ $permohonan->executor?->name ?? 'Staf IT' }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Permohonan ini telah diambil sejak
                                {{ $permohonan->claimed_at?->locale('id')->isoFormat('D MMM Y, HH:mm') ?? '—' }} WIB.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($permohonan->status->value === 'EXECUTED')
            {{-- KONDISI D: SUDAH DIEKSEKUSI --}}
            <div class="bg-white rounded-2xl border border-emerald-200/80 shadow-sm p-5 sm:p-6 animate-fade-up"
                style="animation-delay: 0.15s;">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-emerald-900">Permohonan Telah Sukses Dieksekusi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                @php
                                    $execLog = $permohonan->approvalLogs->where('aksi', 'executed')->last();
                                @endphp
                                @if ($execLog)
                                    Dikerjakan oleh <strong>{{ $execLog->user?->name }}</strong> pada
                                    {{ $execLog->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if ($permohonan->pdf_path)
                            <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-xs transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Unduh Dokumen PDF
                            </a>
                        @else
                            <span
                                class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                PDF sedang digenerate...
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ── 5. Riwayat Aktivitas & Approval Logs ── --}}
        @if ($permohonan->approvalLogs->isNotEmpty())
            @include('approval.partials.approval-log', ['logs' => $permohonan->approvalLogs])
        @endif

        {{-- ── 6. Bottom Navigation ── --}}
        <div class="pt-2 pb-6 flex items-center justify-between">
            <a href="{{ route('eksekusi.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Antrean Eksekusi
            </a>
            <span class="text-xs text-slate-400">eFRUID IT Operations &copy; BPR Artha Pamenang</span>
        </div>

    </div>
@endsection
