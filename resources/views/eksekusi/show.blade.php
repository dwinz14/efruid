@extends('layouts.app')

@section('title', 'Eksekusi Permohonan')
@section('page-title', 'Detail Eksekusi IT')

@section('content')
    <div class="space-y-6">

        {{-- ── 1. Top Navigation & Status Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('eksekusi.riwayat') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke antrean eksekusi">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span
                            class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200 shadow-2xs">
                            {{ $permohonan->nomor_dokumen ?? 'Draft Dokumen' }}
                        </span>
                        @if ($permohonan->form_type->value === 'rangkap')
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Rangkap Jabatan
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                Reguler (Normal)
                            </span>
                        @endif
                        @if ($permohonan->revision_count > 0)
                            <span
                                class="text-[11px] font-semibold text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200">
                                Revisi ke-{{ $permohonan->revision_count }}
                            </span>
                        @endif
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight mt-1 flex items-center gap-2">
                        Eksekusi Permohonan FRUID
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                <span
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $permohonan->status->badgeClass() }}">
                    <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
                    Status: {{ $permohonan->status->label() }}
                </span>
            </div>
        </div>

        {{-- ── 2. Split Main Layout (Grid 12-Kolom) ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- ════════════════════════════════════════════════════════════════
                 KOLOM KIRI: Lembar Dokumen, Stempel Verifikasi & Audit Trail
            ════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">

                {{-- Dokumen FRUID Viewer Frame --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                    style="animation-delay: 0.05s;">
                    <div
                        class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Lembar Dokumen FRUID Final</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Tinjau seluruh rincian hak akses yang akan
                                    dikonfigurasi di USSI</p>
                            </div>
                        </div>
                    </div>

                    {{-- Canvas Viewer Frame --}}
                    <div class="bg-slate-100/90 p-3 sm:p-5">
                        <div class="w-full bg-white shadow-lg rounded-xl border border-slate-200/80 overflow-hidden relative"
                            style="min-height: 760px; height: 82vh;">
                            <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                                style="width: 100%; height: 100%; border: none;" title="Preview Dokumen FRUID"
                                sandbox="allow-same-origin allow-scripts allow-popups">
                            </iframe>
                        </div>
                    </div>
                </div>

                {{-- Rekam Verifikasi Digital (Verification Stamps) --}}
                @if (!empty($permohonan->verification_stamps))
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 animate-fade-up"
                        style="animation-delay: 0.1s;">
                        <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Rekam Verifikasi
                                        Digital (Verification Stamps)</h4>
                                    <p class="text-[11px] text-slate-500">Stempel otentikasi digital dari pejabat penyetuju
                                        sebelumnya</p>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                Terverifikasi Sah
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($permohonan->verification_stamps as $stamp)
                                <div
                                    class="flex items-start gap-3 p-3 bg-slate-50/90 rounded-xl border border-slate-200/70 text-xs hover:border-slate-300 transition-colors">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-2xs font-bold">
                                        ✓
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="font-bold text-slate-800 truncate">
                                                {{ $stamp['role'] ?? 'Verifikator' }}
                                            </p>
                                            <span
                                                class="text-[10px] font-mono text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200">SEAL</span>
                                        </div>
                                        <p class="text-slate-700 font-semibold mt-0.5 truncate">
                                            {{ $stamp['nama'] ?? '—' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $stamp['timestamp'] ?? '—' }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Riwayat Aktivitas & Approval Logs --}}
                @if ($permohonan->approvalLogs->isNotEmpty())
                    <div class="animate-fade-up" style="animation-delay: 0.15s;">
                        @include('approval.partials.approval-log', ['logs' => $permohonan->approvalLogs])
                    </div>
                @endif

            </div>

            {{-- ════════════════════════════════════════════════════════════════
                 KOLOM KANAN: Action Console IT, Profil Pemohon & SOP Guide
            ════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-5 xl:col-span-4 space-y-6 lg:sticky lg:top-6">

                {{-- ── 2.1 KONSOL AKSI EKSEKUSI IT (Action Card Utama) ── --}}
                @if ($permohonan->status->value === 'PENDING_IT')
                    @php $myId = auth()->id(); @endphp

                    @if ($permohonan->pemohon_id === $myId)
                        {{-- KONDISI 0: PERMOHONAN MILIK SENDIRI --}}
                        <div class="bg-amber-50 border border-amber-200/90 rounded-2xl p-5 sm:p-6 shadow-sm animate-fade-up"
                            style="animation-delay: 0.05s;">
                            <div class="flex items-start gap-3.5">
                                <div
                                    class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-700 flex-shrink-0 shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-bold text-amber-900">Aksi Eksekusi Dibatasi</h3>
                                    <p class="text-xs text-amber-800 mt-1.5 leading-relaxed">
                                        Permohonan ini adalah milik Anda sendiri. Sesuai prinsip <em>Segregation of
                                            Duties</em>, staf IT tidak diperbolehkan mengeksekusi hak akses yang diajukan
                                        oleh dirinya sendiri.
                                    </p>
                                    <p class="text-xs text-amber-700 mt-2 font-medium">
                                        Silakan serahkan tugas ini kepada rekan staf IT lain di tim Anda.
                                    </p>

                                    @if ($permohonan->isClaimedBy($myId))
                                        <div class="mt-4 pt-3 border-t border-amber-200">
                                            <p class="text-[11px] font-semibold text-amber-800 mb-2.5">
                                                ⚠ Anda telah mengklaim dokumen ini. Harap lepas klaim agar staf IT lain
                                                dapat mengeksekusi.
                                            </p>
                                            <form action="{{ route('eksekusi.unclaim', $permohonan) }}" method="POST"
                                                onsubmit="return confirm('Lepas klaim permohonan ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs bg-amber-600 text-white hover:bg-amber-700 transition-all active:scale-98">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Lepas Klaim Tugas Ini
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif (!$permohonan->isClaimed())
                        {{-- KONDISI A: BELUM DIKLAIM --}}
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                            style="animation-delay: 0.05s;">
                            <div
                                class="px-5 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-800">Konsol Eksekusi IT</h3>
                                        <p class="text-xs text-slate-500">Tugas tersedia untuk dieksekusi</p>
                                    </div>
                                </div>
                                <span
                                    class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                    Tersedia
                                </span>
                            </div>

                            <div class="p-5 space-y-4">
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Klaim permohonan ini untuk menandai bahwa Anda sedang menangani konfigurasi User ID atau
                                    hak akses di sistem <strong>USSI Core Banking</strong>.
                                </p>

                                <form action="{{ route('eksekusi.claim', $permohonan) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl font-bold text-sm bg-brand-600 text-white hover:bg-brand-700 shadow-md shadow-brand-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/50 active:scale-98">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Ambil & Mulai Kerjakan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif ($permohonan->isClaimedBy($myId))
                        {{-- KONDISI B: DIKLAIM OLEH SAYA --}}
                        <div class="bg-white rounded-2xl border-2 border-emerald-500/80 shadow-md shadow-emerald-500/5 overflow-hidden animate-fade-up"
                            style="animation-delay: 0.05s;" x-data="{ confirm: false, loading: false }">

                            {{-- Console Header --}}
                            <div
                                class="px-5 py-4 border-b border-emerald-100 bg-emerald-50/60 flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700 shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-emerald-900">Konsol Eksekusi Aktif</h3>
                                        <p class="text-[11px] text-emerald-700 font-medium">
                                            Diambil:
                                            {{ $permohonan->claimed_at?->locale('id')->isoFormat('D MMM, HH:mm') ?? 'Baru saja' }}
                                            WIB
                                        </p>
                                    </div>
                                </div>

                                <form action="{{ route('eksekusi.unclaim', $permohonan) }}" method="POST"
                                    onsubmit="return confirm('Lepas klaim permohonan ini? Tugas akan kembali berstatus tersedia untuk staf IT lain.')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Lepas klaim agar bisa diambil staf IT lain">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Lepas Klaim
                                    </button>
                                </form>
                            </div>

                            <div class="p-5 space-y-4">
                                {{-- Petunjuk Eksekusi --}}
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs space-y-1.5">
                                    <div class="flex items-center gap-1.5 text-slate-800 font-bold">
                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>Konfirmasi Eksekusi USSI</span>
                                    </div>
                                    <p class="text-slate-600 leading-relaxed">
                                        Nama Anda (<strong>{{ auth()->user()->name }}</strong>) akan dicatat resmi sebagai
                                        <em>Administrator Aplikasi USSI</em> dan file PDF final bertanda tangan digital
                                        lengkap akan otomatis dibuat.
                                    </p>
                                </div>

                                {{-- Step 1: Tombol Pembuka Form --}}
                                <div x-show="!confirm">
                                    <button type="button" @click="confirm = true"
                                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50 active:scale-98">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tandai Selesai Dieksekusi di USSI
                                    </button>
                                </div>

                                {{-- Step 2: Form Konfirmasi Eksekusi --}}
                                <div x-show="confirm" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="space-y-3.5 pt-2 border-t border-slate-100">

                                    <form method="POST" action="{{ route('eksekusi.execute', $permohonan) }}"
                                        @submit="loading = true">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Catatan Eksekusi IT <span
                                                    class="text-slate-400 font-normal">(opsional)</span>
                                            </label>
                                            <textarea name="catatan" rows="2" class="input w-full text-xs"
                                                placeholder="Contoh: User ID telah disesuaikan dengan wewenang Teller di USSI..." maxlength="255"></textarea>
                                        </div>

                                        <div class="flex items-center gap-2.5 mt-3">
                                            <button type="submit"
                                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-bold text-xs sm:text-sm bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50 disabled:opacity-50 disabled:cursor-not-allowed active:scale-98"
                                                :disabled="loading">
                                                <svg x-show="loading" class="animate-spin w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                                </svg>
                                                <span
                                                    x-text="loading ? 'Memproses...' : 'Konfirmasi Selesai Eksekusi'"></span>
                                            </button>

                                            <button type="button" @click="confirm = false"
                                                class="btn-secondary py-3 px-3.5 text-xs font-bold" :disabled="loading">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- KONDISI C: DIKLAIM REKAN IT LAIN --}}
                        <div class="bg-white rounded-2xl border border-amber-200/90 shadow-sm p-5 animate-fade-up"
                            style="animation-delay: 0.05s;">
                            <div class="flex items-start gap-3.5">
                                <div
                                    class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 flex-shrink-0 shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 mb-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Sedang Dikerjakan
                                    </span>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        {{ $permohonan->executor?->name ?? 'Staf IT' }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Permohonan ini telah diklaim sejak
                                        {{ $permohonan->claimed_at?->locale('id')->isoFormat('D MMM Y, HH:mm') ?? '—' }}
                                        WIB.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif($permohonan->status->value === 'EXECUTED')
                    {{-- KONDISI D: SUDAH DIEKSEKUSI --}}
                    <div class="bg-white rounded-2xl border-2 border-emerald-500/80 shadow-sm p-5 sm:p-6 animate-fade-up"
                        style="animation-delay: 0.05s;">
                        <div class="flex items-start gap-3.5">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span
                                    class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 mb-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Eksekusi Sukses
                                </span>
                                <h3 class="text-sm font-bold text-emerald-900">Telah Selesai Dikonfigurasi</h3>
                                @php
                                    $execLog = $permohonan->approvalLogs->where('aksi', 'executed')->last();
                                @endphp
                                <p class="text-xs text-slate-600 mt-1">
                                    @if ($execLog)
                                        Dikerjakan oleh <strong>{{ $execLog->user?->name }}</strong> pada
                                        {{ $execLog->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
                                    @else
                                        Dikerjakan oleh
                                        <strong>{{ $permohonan->executor?->name ?? 'Staf IT' }}</strong>
                                    @endif
                                </p>

                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    @if ($permohonan->pdf_path)
                                        <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-all active:scale-98">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Unduh Lembar FRUID
                                        </a>
                                    @else
                                        <span
                                            class="inline-block w-full text-center text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-2 rounded-xl border border-slate-200">
                                            PDF sedang diproses...
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── 2.2 Ringkasan Metadata Pemohon & Kantor ── --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4 animate-fade-up"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Pemohon</h4>
                        <span class="text-[11px] font-mono text-slate-500 font-bold">
                            {{ $permohonan->pemohon?->nik ?? '—' }}
                        </span>
                    </div>

                    {{-- Pemohon Profile --}}
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-2xs border border-brand-200">
                            {{ strtoupper(substr($permohonan->pemohon?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">
                                {{ $permohonan->pemohon?->name ?? '—' }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $permohonan->pemohon?->jabatan_label ?? ($permohonan->pemohon?->jabatan ?? 'Staff') }}
                            </p>
                        </div>
                    </div>

                    {{-- Detail List --}}
                    <div class="space-y-2.5 pt-2 border-t border-slate-100 text-xs">
                        {{-- Kantor --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-400 font-medium">Kantor:</span>
                            <span class="text-slate-700 font-bold text-right truncate">
                                {{ $permohonan->kantor?->label ?? ($permohonan->kantor?->nama ?? '—') }}
                            </span>
                        </div>

                        {{-- Jenis Akses --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-400 font-medium">Jenis:</span>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $permohonan->jenis_permohonan->label() }}
                            </span>
                        </div>

                        {{-- Tanggal Pengajuan --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-400 font-medium">Pengajuan:</span>
                            <span class="text-slate-700 font-semibold">
                                {{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMM Y') ?? $permohonan->created_at->locale('id')->isoFormat('D MMM Y') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
