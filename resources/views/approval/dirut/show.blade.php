@extends('layouts.app')

@section('title', 'Proses Permohonan — Direktur')
@section('page-title', 'Persetujuan Direktur')

@section('content')
    @php
        $isRangkap = $permohonan->form_type->value === 'rangkap';
        $isPendingAtasan = $permohonan->status === \App\Enums\StatusPermohonan::PENDING_ATASAN;
        $isPendingDirut = $permohonan->status === \App\Enums\StatusPermohonan::PENDING_DIRUT;
        $canApprove = $canApproveAsAtasan || $canApproveAsDirut;
    @endphp

    <div class="space-y-6">

        {{-- ── 1. Top Navigation & Status Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('approval.dirut.index') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke antrean approval direktur">
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
                        @if ($isRangkap)
                            <span
                                class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Rangkap Jabatan
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-200">
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
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
                {{-- Context Capacity Badge --}}
                @if ($canApproveAsAtasan)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                        Kapasitas: Atasan Langsung
                    </span>
                @elseif($canApproveAsDirut)
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Kapasitas: Direktur Utama
                    </span>
                @endif

                {{-- Status Pill --}}
                <span
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $permohonan->status->badgeClass() }}">
                    <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
                    Status: {{ $permohonan->status->label() }}
                </span>
            </div>
        </div>

        {{-- ── 2. Split Main Layout (Grid 12-Kolom Seimbang) ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- ════════════════════════════════════════════════════════════════
                 KOLOM KIRI: Lembar Dokumen FRUID, Verification Stamps, & Audit Trail
            ════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">

                {{-- Dokumen FRUID Viewer Frame --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                    style="animation-delay: 0.05s;">
                    {{-- Toolbar Dokumen --}}
                    <div
                        class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between flex-wrap gap-3">
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
                                <h3 class="text-sm font-bold text-slate-800">Lembar Dokumen FRUID (Pratinjau Asli)</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Periksa seluruh rincian hak akses, kantor, dan
                                    komitmen pemohon sebelum otorisasi</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Dokumen Aktif
                            </span>
                        </div>
                    </div>

                    {{-- Canvas Viewer Frame dengan Loading Skeleton --}}
                    <div class="bg-slate-100/90 p-3 sm:p-5" x-data="{ loaded: false }">
                        <div class="w-full bg-white shadow-lg rounded-xl border border-slate-200/80 overflow-hidden relative"
                            style="min-height: 760px; height: 82vh;">

                            {{-- Skeleton Loading Spinner --}}
                            <div x-show="!loaded" x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-4 bg-slate-50">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 shadow-xs">
                                    <svg class="h-6 w-6 animate-spin text-brand-600" fill="none" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-slate-800">Memuat Lembar Dokumen FRUID</p>
                                    <p class="mt-0.5 text-xs text-slate-500">Menyiapkan pratinjau dokumen untuk pemeriksaan
                                        Direksi...
                                    </p>
                                </div>
                            </div>

                            <iframe src="{{ route('dokumen.preview', $permohonan) }}" @load="loaded = true"
                                style="width: 100%; height: 100%; border: none;" title="Preview Dokumen FRUID"
                                sandbox="allow-same-origin allow-scripts allow-popups">
                            </iframe>
                        </div>
                    </div>
                </div>

                {{-- Rekam Verifikasi Digital (Verification Stamps) jika ada --}}
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
                                        Digital</h4>
                                    <p class="text-[11px] text-slate-500">Stempel otentikasi digital yang telah tersemat
                                        pada dokumen</p>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                Otentik Terverifikasi
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
                 KOLOM KANAN: Konsol Keputusan Direktur, Profil Pemohon, & SOP
            ════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-5 xl:col-span-4 space-y-6 lg:sticky lg:top-6">

                {{-- ── 2.1 KONSOL KEPUTUSAN DIREKTUR (Action Console Utama) ── --}}
                @if ($canApprove)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                        style="animation-delay: 0.05s;" x-data="approvalAction()">

                        <div
                            class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Konsol Keputusan Direktur</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        @if ($canApproveAsAtasan)
                                            Otorisasi selaku Atasan Langsung pemohon
                                        @else
                                            Otorisasi final tingkat Direktur Utama
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            {{-- Pilihan Aksi: Setujui vs Tolak --}}
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Tombol Pilihan Setujui --}}
                                <button type="button" @click="setAction('approve')"
                                    class="p-3.5 rounded-xl border-2 border-emerald-200 bg-emerald-50/60
                                    flex flex-col items-center justify-center gap-2 transition-all duration-200
                                    text-center focus:outline-none hover:border-emerald-400 hover:bg-emerald-100 hover:shadow-sm"
                                    :class="action === 'approve'
                                        ?
                                        'border-emerald-500 bg-emerald-200 shadow-sm ring-2 ring-emerald-500/20' :
                                        ''">

                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-200"
                                        :class="action === 'approve'
                                            ?
                                            'bg-emerald-600 text-white' :
                                            'bg-emerald-100 text-emerald-600'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-xs font-bold transition-colors"
                                            :class="action === 'approve' ? 'text-emerald-900' : 'text-emerald-800'">
                                            Setujui
                                        </p>
                                        <p class="text-[10px] mt-0.5 transition-colors"
                                            :class="action === 'approve' ? 'text-emerald-700' : 'text-emerald-600'">
                                            Otorisasi dokumen
                                        </p>
                                    </div>
                                </button>

                                {{-- Tombol Pilihan Tolak --}}
                                <button type="button" @click="setAction('reject')"
                                    class="group p-3.5 rounded-xl border-2 border-red-200 bg-red-50/60
                                    flex flex-col items-center justify-center gap-2 transition-all duration-200
                                    text-center focus:outline-none hover:border-red-400 hover:bg-red-100 hover:shadow-sm"
                                    :class="action === 'reject'
                                        ?
                                        'border-red-500 bg-red-200 shadow-sm ring-2 ring-red-500/20' :
                                        ''">

                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors duration-200"
                                        :class="action === 'reject'
                                            ?
                                            'bg-red-600 text-white' :
                                            'bg-red-100 text-red-600 group-hover:bg-red-200'">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-xs font-bold transition-colors"
                                            :class="action === 'reject' ? 'text-red-900' : 'text-red-800'">
                                            Tolak
                                        </p>
                                        <p class="text-[10px] mt-0.5 transition-colors"
                                            :class="action === 'reject' ? 'text-red-700' : 'text-red-600'">
                                            Kembalikan berkas
                                        </p>
                                    </div>
                                </button>
                            </div>

                            {{-- Petunjuk sebelum opsi dipilih --}}
                            <div x-show="!showApprove && !showReject"
                                class="rounded-xl border border-dashed border-slate-300 bg-slate-50/60 p-4 text-center text-xs text-slate-500">
                                Pilih salah satu keputusan di atas untuk memproses permohonan ini.
                            </div>

                            {{-- ── Form Detail Persetujuan (Approve) ── --}}
                            <div x-show="showApprove" x-cloak x-transition.opacity.duration.200ms
                                class="space-y-4 pt-2 border-t border-slate-100">

                                <div
                                    class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3 text-emerald-900">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="text-xs space-y-1">
                                        <p class="font-bold text-emerald-950">Konfirmasi Otorisasi Direksi</p>
                                        <p class="leading-relaxed text-emerald-800">
                                            @if ($canApproveAsAtasan)
                                                @if ($isRangkap)
                                                    Anda menyetujui dokumen ini dalam kapasitas sebagai <strong
                                                        class="font-bold text-emerald-950">Atasan Langsung</strong>.
                                                    Selanjutnya berkas akan diteruskan ke antrean <strong
                                                        class="font-bold text-emerald-950">Persetujuan Direktur
                                                        Utama</strong>.
                                                @else
                                                    Anda menyetujui dokumen ini dalam kapasitas sebagai <strong
                                                        class="font-bold text-emerald-950">Atasan Langsung</strong>.
                                                    Dokumen akan langsung dialihkan ke tahap <strong
                                                        class="font-bold text-emerald-950">Eksekusi Tim IT</strong>.
                                                @endif
                                            @else
                                                Persetujuan anda sebagai <strong
                                                    class="font-bold text-emerald-950">Direktur Utama</strong> merupakan
                                                otorisasi final. Dokumen akan langsung diteruskan ke <strong
                                                    class="font-bold text-emerald-950">Tim IT untuk Eksekusi
                                                    Teknis</strong>.
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <form method="POST"
                                    action="{{ $canApproveAsAtasan ? route('approval.dirut.approveAsAtasan', $permohonan) : route('approval.dirut.approve', $permohonan) }}"
                                    @submit="loading = true">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-sm bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="loading">
                                        <x-spinner x-show="loading" x-cloak />
                                        <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span
                                            x-text="loading ? 'Memproses Otorisasi...' : 'Konfirmasi & Setujui Dokumen'"></span>
                                    </button>
                                </form>
                            </div>

                            {{-- ── Form Detail Penolakan (Reject) ── --}}
                            <div x-show="showReject" x-cloak x-transition.opacity.duration.200ms
                                class="space-y-4 pt-2 border-t border-slate-100">

                                <div
                                    class="p-3.5 rounded-xl bg-red-50 border border-red-200/80 flex items-start gap-3 text-red-900">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <div class="text-xs space-y-1">
                                        <p class="font-bold text-red-950">Konfirmasi Penolakan Berkas</p>
                                        <p class="leading-relaxed text-red-800">
                                            Permohonan akan dihentikan dan pemohon akan menerima pemberitahuan resmi beserta
                                            catatan evaluasi penolakan.
                                        </p>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('approval.dirut.reject', $permohonan) }}"
                                    @submit="loading = true">
                                    @csrf
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="text-xs font-bold text-slate-700">
                                                Alasan Penolakan <span class="text-red-500">*</span>
                                            </label>
                                            <span class="text-[11px] font-mono font-medium"
                                                :class="alasan.trim().length < 10 ? 'text-amber-600 font-bold' :
                                                    'text-slate-400'"
                                                x-text="alasan.trim().length + ' / 500 (min. 10)'"></span>
                                        </div>
                                        <textarea name="alasan_reject" rows="3"
                                            class="input w-full rounded-xl @error('alasan_reject') input-error @enderror"
                                            placeholder="Tuliskan secara spesifik alasan penolakan atau perbaikan yang dibutuhkan..." x-model="alasan"
                                            required minlength="10" maxlength="500"></textarea>
                                        @error('alasan_reject')
                                            <p class="field-error mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="mt-3 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-sm bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="loading || alasan.trim().length < 10">
                                        <x-spinner x-show="loading" x-cloak />
                                        <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span
                                            x-text="loading ? 'Memproses Penolakan...' : 'Konfirmasi Tolak Permohonan'"></span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Footer Info --}}
                        <div
                            class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Keputusan tercatat audit trail
                            </span>
                            <a href="{{ route('approval.dirut.index') }}"
                                class="font-semibold text-brand-700 hover:text-brand-800 hover:underline">
                                Antrean &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Status Saat Ini Bukan Status yang Memerlukan Aksi Direktur --}}
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm text-center">
                        <div
                            class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Permohonan Telah Diproses</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Status dokumen saat ini: <span
                                class="font-bold text-slate-800">{{ $permohonan->status->label() }}</span>.
                            Tidak ada tindakan persetujuan direksi yang diperlukan saat ini.
                        </p>
                        <a href="{{ route('approval.dirut.index') }}"
                            class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            Kembali ke Antrean
                        </a>
                    </div>
                @endif

                {{-- ── 2.2 Rangkuman Detail Pengajuan & Profil Pemohon ── --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4 animate-fade-up"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3.5">
                        <div
                            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-brand-600 text-white font-bold text-sm shadow-xs">
                            {{ strtoupper(substr($permohonan->pemohon?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-bold text-slate-900 truncate">{{ $permohonan->pemohon?->name ?? '—' }}
                            </h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span
                                    class="font-mono text-[11px] font-semibold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                    {{ $permohonan->pemohon?->nik ?? '—' }}
                                </span>
                                <span class="text-xs text-slate-400">·</span>
                                <span
                                    class="text-xs text-slate-500 truncate">{{ $permohonan->kantor?->nama ?? 'Pusat' }}</span>
                            </div>
                        </div>
                    </div>

                    <dl class="space-y-2.5 text-xs">
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Jabatan Definitif</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $permohonan->pemohon?->jabatan_label ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Kantor Penempatan</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $permohonan->kantor?->label ?? ($permohonan->kantor?->nama ?? '—') }}</dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Format Formulir</dt>
                            <dd class="text-right">
                                <span class="font-semibold {{ $isRangkap ? 'text-amber-800' : 'text-brand-800' }}">
                                    {{ $isRangkap ? 'Rangkap Jabatan' : 'Tidak Rangkap (Reguler)' }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Jenis Permohonan</dt>
                            <dd class="text-right">
                                <span
                                    class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-bold text-brand-700 border border-brand-200">
                                    {{ $permohonan->jenis_permohonan->label() }}
                                </span>
                            </dd>
                        </div>
                        @if ($permohonan->jenis_permohonan->value === 'perubahan' && $permohonan->jabatan_baru)
                            <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                                <dt class="text-slate-500">Jabatan Tujuan</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $permohonan->jabatan_baru }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Hak Akses USSI</dt>
                            <dd class="text-right">
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-800">
                                    {{ $permohonan->access_level?->value ?? 'USER' }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">User ID Sistem</dt>
                            <dd class="font-mono font-semibold text-slate-800 text-right">
                                {{ $permohonan->user_id_ussi ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1">
                            <dt class="text-slate-500">Tanggal Pengajuan</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMMM Y') ?? $permohonan->created_at->locale('id')->isoFormat('D MMMM Y') }}
                            </dd>
                        </div>
                    </dl>

                    {{-- Informasi Persetujuan Atasan Sebelumnya --}}
                    @if ($permohonan->atasan)
                        <div class="mt-2 p-3 bg-slate-50 rounded-xl border border-slate-200/70 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Atasan Langsung:</span>
                                <span class="font-bold text-slate-800">{{ $permohonan->atasan->name }}</span>
                            </div>
                            @if ($permohonan->atasan_approved_at)
                                <div class="flex items-center justify-between mt-1 pt-1 border-t border-slate-200/50">
                                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Disetujui
                                    </span>
                                    <span class="text-slate-500 text-[11px]">
                                        {{ $permohonan->atasan_approved_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- ── 2.3 Tahapan Alur Otorisasi ── --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 animate-fade-up"
                    style="animation-delay: 0.15s;">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tahapan Alur FRUID</h3>
                    </div>

                    <div class="mt-3.5 space-y-2.5 text-xs">
                        {{-- Langkah 1: Pengajuan --}}
                        <div class="flex items-center gap-2.5 text-emerald-700">
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">✓</span>
                            <span class="font-semibold">Pemohon Mengajukan Dokumen</span>
                        </div>

                        {{-- Langkah 2: Atasan --}}
                        @if ($isPendingAtasan)
                            <div
                                class="flex items-center gap-2.5 text-brand-900 bg-brand-50/70 p-2 rounded-xl border border-brand-200/80">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-white font-bold text-[10px]">2</span>
                                <span class="font-bold">Otorisasi Atasan Langsung
                                    {{ $canApproveAsAtasan ? '(Anda)' : '' }}</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2.5 text-emerald-700">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">✓</span>
                                <span class="font-semibold">Otorisasi Atasan Langsung Disetujui</span>
                            </div>
                        @endif

                        {{-- Langkah 3: Dirut (jika rangkap atau relevan) --}}
                        @if ($isRangkap)
                            @if ($isPendingDirut)
                                <div
                                    class="flex items-center gap-2.5 text-blue-900 bg-blue-50/70 p-2 rounded-xl border border-blue-200/80">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white font-bold text-[10px]">3</span>
                                    <span class="font-bold">Persetujuan Direktur Utama (Saat Ini)</span>
                                </div>
                            @elseif ($isPendingAtasan)
                                <div class="flex items-center gap-2.5 text-slate-500">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-slate-500 font-bold text-[10px]">3</span>
                                    <span>Persetujuan Direktur Utama</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 text-emerald-700">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">✓</span>
                                    <span class="font-semibold">Persetujuan Direktur Utama Selesai</span>
                                </div>
                            @endif
                        @endif

                        {{-- Langkah 4: IT --}}
                        <div
                            class="flex items-center gap-2.5 {{ in_array($permohonan->status->value, ['pending_it', 'diproses_it']) ? 'text-brand-900 bg-brand-50/70 p-2 rounded-xl border border-brand-200/80 font-bold' : ($permohonan->status->value === 'selesai' ? 'text-emerald-700 font-semibold' : 'text-slate-400') }}">
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full {{ $permohonan->status->value === 'selesai' ? 'bg-emerald-100 text-emerald-700' : (in_array($permohonan->status->value, ['pending_it', 'diproses_it']) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500') }} font-bold text-[10px]">
                                {{ $permohonan->status->value === 'selesai' ? '✓' : ($isRangkap ? '4' : '3') }}
                            </span>
                            <span>Konfigurasi & Eksekusi Sistem IT</span>
                        </div>
                    </div>
                </div>

                {{-- ── 2.4 Panduan Singkat Otorisasi Direksi (SOP) ── --}}
                <div class="bg-blue-50/60 rounded-2xl border border-blue-200/80 p-4 text-xs text-blue-900 shadow-2xs">
                    <div class="flex items-start gap-2.5">
                        <svg class="h-4 w-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="space-y-1">
                            <p class="font-bold text-blue-950">Pedoman Verifikasi Tingkat Direksi</p>
                            <p class="text-blue-900/80 leading-relaxed">
                                Pastikan pemberian hak akses ganda (rangkap jabatan) atau akses operasional kritis memiliki
                                landasan operasional dan mitigasi risiko tata kelola (governance) yang memadai.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Script Interaksi Keputusan Approval Direktur --}}
    <script>
        function approvalAction() {
            return {
                action: '',
                alasan: '',
                loading: false,
                setAction(val) {
                    this.action = this.action === val ? '' : val;
                },
                get showReject() {
                    return this.action === 'reject';
                },
                get showApprove() {
                    return this.action === 'approve';
                },
            }
        }
    </script>
@endsection
