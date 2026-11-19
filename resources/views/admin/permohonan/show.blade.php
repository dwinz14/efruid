@extends('layouts.app')

@section('title', 'Detail Permohonan — Monitoring Admin')
@section('page-title', 'Detail Permohonan (Admin)')

@section('content')
    @php
        $isRangkap = $permohonan->form_type->value === 'rangkap';
        $status = $permohonan->status;
        $isPendingAtasan = $status === \App\Enums\StatusPermohonan::PENDING_ATASAN;
        $isPendingDirut = $status === \App\Enums\StatusPermohonan::PENDING_DIRUT;
        $isPendingIt = $status === \App\Enums\StatusPermohonan::PENDING_IT;
        $isExecuted = $status === \App\Enums\StatusPermohonan::EXECUTED;
        $isRejected = $status === \App\Enums\StatusPermohonan::REJECTED;
        $isCancelled = $status === \App\Enums\StatusPermohonan::CANCELLED;
        $isDraft = $status === \App\Enums\StatusPermohonan::DRAFT;
    @endphp

    <div class="space-y-6">

        {{-- ── 1. Top Navigation & Status Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.permohonan.index') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke monitoring permohonan">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span
                            class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200 shadow-2xs">
                            {{ $permohonan->nomor_dokumen ?? '— Menunggu Nomor Dokumen —' }}
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
                {{-- Unduh PDF --}}
                @if ($permohonan->pdf_path)
                    <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200/90 shadow-sm hover:bg-slate-50 hover:text-brand-600 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95">
                        <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Unduh PDF</span>
                    </a>
                @endif


                {{-- Status Pill --}}
                <span
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $status->badgeClass() }}">
                    <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
                    Status: {{ $status->label() }}
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
                                <h3 class="text-sm font-bold text-slate-800">Lembar Dokumen FRUID (Pratinjau Resmi)</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Salinan otentik formulir permohonan hak akses
                                    sistem USSI</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Dokumen Terdaftar
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
                                    <p class="mt-0.5 text-xs text-slate-500">Menyiapkan pratinjau dokumen resmi...
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
                                        pada dokumen ini</p>
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
                 KOLOM KANAN: Ringkasan Status, Profil Pemohon, Alur & SOP
            ════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-5 xl:col-span-4 space-y-6 lg:sticky lg:top-6">

                {{-- ── 2.1 Ringkasan Status Pengawasan Dokumen ── --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4 animate-fade-up"
                    style="animation-delay: 0.05s;">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Status</h3>
                            </div>
                        </div>

                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $status->badgeClass() }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
                            {{ $status->label() }}
                        </span>
                    </div>

                    <div
                        class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                        @if ($isPendingAtasan)
                            Berkas sedang berada dalam antrean <strong class="text-amber-800 font-bold">Persetujuan Atasan
                                Langsung</strong> ({{ $permohonan->atasan?->name ?? 'Belum ditentukan' }}).
                        @elseif ($isPendingDirut)
                            Berkas telah disetujui atasan langsung dan sedang menunggu <strong
                                class="text-blue-800 font-bold">Persetujuan Direktur Utama</strong> (khusus formulir
                            rangkap jabatan).
                        @elseif ($isPendingIt)
                            Berkas telah selesai diotorisasi pimpinan dan kini menunggu <strong
                                class="text-indigo-800 font-bold">Eksekusi Teknis oleh Tim IT</strong>.
                        @elseif ($isExecuted)
                            Berkas telah <strong class="text-emerald-800 font-bold">Selesai Dikonfigurasi</strong> dan hak
                            akses pemohon telah diaktifkan pada sistem perbankan.
                        @elseif ($isRejected)
                            Berkas <strong class="text-rose-800 font-bold">Ditolak / Dikembalikan</strong> pada tahap
                            evaluasi persetujuan.
                        @elseif ($isCancelled)
                            Permohonan ini telah <strong class="text-slate-800 font-bold">Dibatalkan</strong> oleh pemohon.
                        @else
                            Permohonan masih berstatus draft dan belum dikirimkan untuk otorisasi.
                        @endif
                    </div>

                    @if ($permohonan->pdf_path)
                        <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-brand-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Unduh Dokumen PDF Resmi</span>
                        </a>
                    @endif
                </div>

                {{-- ── 2.2 Catatan Penolakan (Jika Ada) ── --}}
                @if ($permohonan->alasan_reject)
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 sm:p-5 text-xs text-rose-950 shadow-2xs animate-fade-up"
                        style="animation-delay: 0.08s;">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1 space-y-1">
                                <p class="font-bold text-rose-900 text-sm">Catatan Penolakan Berkas</p>
                                <p
                                    class="leading-relaxed text-rose-800 bg-white/80 p-3 rounded-xl border border-rose-200/80 mt-1.5">
                                    {{ $permohonan->alasan_reject }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── 2.3 Rangkuman Detail Pengajuan & Profil Pemohon ── --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4 animate-fade-up"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3.5">
                        <div
                            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-brand-600 text-white font-bold text-sm shadow-xs">
                            {{ strtoupper(substr($permohonan->pemohon?->name ?? ($permohonan->nama_pemohon ?? 'P'), 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-bold text-slate-900 truncate">
                                {{ $permohonan->pemohon?->name ?? ($permohonan->nama_pemohon ?? '—') }}</h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span
                                    class="font-mono text-[11px] font-semibold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                    {{ $permohonan->nik_pemohon ?? ($permohonan->pemohon?->nik ?? '—') }}
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
                                {{ $permohonan->pemohon?->jabatan_label ?? ($permohonan->jabatan_pemohon ?? '—') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Kantor Penempatan</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $permohonan->kantor?->label ?? ($permohonan->kantor?->nama ?? '—') }}
                            </dd>
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
                        @if ($permohonan->jenis_permohonan->value === 'perubahan')
                            @if ($permohonan->tipe_perubahan)
                                <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                                    <dt class="text-slate-500">Tipe Perubahan</dt>
                                    <dd class="font-semibold text-slate-800 text-right">
                                        {{ ucfirst($permohonan->tipe_perubahan->value ?? '—') }}
                                    </dd>
                                </div>
                            @endif
                            @if ($permohonan->jabatan_baru)
                                <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                                    <dt class="text-slate-500">Jabatan Tujuan</dt>
                                    <dd class="font-semibold text-slate-800 text-right">{{ $permohonan->jabatan_baru }}
                                    </dd>
                                </div>
                            @endif
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
                                {{ $permohonan->user_id_ussi ?? '—' }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1">
                            <dt class="text-slate-500">Tanggal Pengajuan</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMMM Y') ?? $permohonan->created_at->locale('id')->isoFormat('D MMMM Y') }}
                            </dd>
                        </div>
                    </dl>

                    {{-- Informasi Atasan Langsung --}}
                    @if ($permohonan->atasan || $permohonan->nama_atasan_ttd)
                        <div class="mt-2 p-3 bg-slate-50 rounded-xl border border-slate-200/70 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Atasan Langsung:</span>
                                <span class="font-bold text-slate-800">
                                    {{ $permohonan->atasan?->name ?? $permohonan->nama_atasan_ttd }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Informasi Eksekutor IT --}}
                    @if ($permohonan->executor || $permohonan->nama_executor)
                        <div class="mt-2 p-3 bg-indigo-50/60 rounded-xl border border-indigo-100 text-xs text-indigo-950">
                            <div class="flex items-center justify-between">
                                <span class="text-indigo-700 font-medium">Petugas IT Eksekusi:</span>
                                <span class="font-bold text-indigo-900">
                                    {{ $permohonan->executor?->name ?? $permohonan->nama_executor }}
                                </span>
                            </div>
                            @if ($permohonan->claimed_at)
                                <div
                                    class="flex items-center justify-between mt-1 pt-1 border-t border-indigo-200/50 text-[11px] text-indigo-700">
                                    <span>Waktu Eksekusi:</span>
                                    <span>{{ $permohonan->claimed_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- ── 2.4 Tahapan Alur FRUID (Workflow Pipeline Tracker) ── --}}
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

                        {{-- Langkah 2: Otorisasi Atasan --}}
                        @if ($isPendingAtasan)
                            <div
                                class="flex items-center gap-2.5 text-amber-900 bg-amber-50/70 p-2 rounded-xl border border-amber-200/80">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-white font-bold text-[10px]">2</span>
                                <span class="font-bold">Otorisasi Atasan Langsung (Menunggu)</span>
                            </div>
                        @elseif ($isDraft)
                            <div class="flex items-center gap-2.5 text-slate-400">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-slate-500 font-bold text-[10px]">2</span>
                                <span>Otorisasi Atasan Langsung</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2.5 text-emerald-700">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">✓</span>
                                <span class="font-semibold">Otorisasi Atasan Langsung Selesai</span>
                            </div>
                        @endif

                        {{-- Langkah 3: Persetujuan Direktur Utama (Jika Rangkap) --}}
                        @if ($isRangkap)
                            @if ($isPendingDirut)
                                <div
                                    class="flex items-center gap-2.5 text-blue-900 bg-blue-50/70 p-2 rounded-xl border border-blue-200/80">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white font-bold text-[10px]">3</span>
                                    <span class="font-bold">Persetujuan Direktur Utama (Menunggu)</span>
                                </div>
                            @elseif ($isPendingAtasan || $isDraft)
                                <div class="flex items-center gap-2.5 text-slate-400">
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

                        {{-- Langkah 4: IT Eksekusi --}}
                        @if ($isPendingIt)
                            <div
                                class="flex items-center gap-2.5 text-indigo-900 bg-indigo-50/70 p-2 rounded-xl border border-indigo-200/80 font-bold">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-[10px]">{{ $isRangkap ? '4' : '3' }}</span>
                                <span>Konfigurasi & Eksekusi Sistem IT (Menunggu)</span>
                            </div>
                        @elseif ($isExecuted)
                            <div class="flex items-center gap-2.5 text-emerald-700">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">✓</span>
                                <span class="font-semibold">Konfigurasi & Eksekusi IT Selesai</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2.5 text-slate-400">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-slate-500 font-bold text-[10px]">{{ $isRangkap ? '4' : '3' }}</span>
                                <span>Konfigurasi & Eksekusi Sistem IT</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── 2.5 Catatan Tata Kelola Pengawasan Admin (SOP) ── --}}
                <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-4 text-xs text-slate-700 shadow-2xs">
                    <div class="flex items-start gap-2.5">
                        <svg class="h-4 w-4 text-slate-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <div class="space-y-1">
                            <p class="font-bold text-slate-900">Tata Kelola & Kepatuhan Audit</p>
                            <p class="text-slate-600 leading-relaxed">
                                Halaman ini merupakan konsol pengawasan administratif sistem FRUID. Seluruh perubahan
                                status, stempel otentikasi digital, dan eksekusi hak akses terekam secara permanen pada
                                audit log.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
