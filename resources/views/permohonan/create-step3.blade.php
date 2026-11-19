@extends('layouts.app')

@section('title', 'Periksa & Kirim Permohonan')
@section('page-title', 'Buat Permohonan')

@section('content')
    @php
        $isRangkap = $permohonan->form_type->value === 'rangkap';
        $step2Parameters = array_filter(
            [
                'form_type' => $permohonan->form_type->value,
                'draft_id' => $permohonan->id,
            ],
            fn($value) => filled($value),
        );
    @endphp

    <div class="space-y-6">
        {{-- Stepper Navigation --}}
        @include('permohonan.partials.stepper', ['step' => 3])

        {{-- Header Halaman --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700 border border-brand-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                            Langkah 03 / 03
                        </span>
                        <span class="text-xs text-slate-400">·</span>
                        <span class="text-xs font-medium text-slate-500">Konfirmasi Akhir</span>
                    </div>

                    <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                        Tinjau & Kirim Permohonan FRUID
                    </h2>
                    <p class="mt-1 text-sm text-slate-600 max-w-2xl leading-relaxed">
                        Periksa kembali kesesuaian data pada dokumen pratinjau di bawah ini. Setelah dikirim, dokumen akan
                        masuk ke antrean otorisasi dan tidak dapat disunting kembali.
                    </p>
                </div>

                <div class="flex-shrink-0 self-start md:self-auto">
                    <span
                        class="inline-flex items-center gap-2 rounded-xl border px-3.5 py-2 text-xs font-bold shadow-xs {{ $isRangkap ? 'border-amber-200 bg-amber-50 text-amber-900' : 'border-brand-200 bg-brand-50 text-brand-900' }}">
                        <span class="h-2 w-2 rounded-full {{ $isRangkap ? 'bg-amber-500' : 'bg-brand-600' }}"></span>
                        {{ $isRangkap ? 'Format Rangkap Jabatan (3 Tanda Tangan)' : 'Format Reguler (2 Tanda Tangan)' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Grid Utama: Pratinjau Dokumen (Kiri) & Konsol Ringkasan (Kanan) --}}
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem] xl:items-start">

            {{-- ══════════════════ KOLOM KIRI: CANVAS DOKUMEN ══════════════════ --}}
            <section aria-labelledby="document-preview-heading"
                class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                {{-- Toolbar Dokumen --}}
                <div
                    class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50/80 px-5 py-3.5 sm:px-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white shadow-xs">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <div>
                            <h3 id="document-preview-heading" class="text-sm font-bold text-slate-900">
                                Pratinjau Dokumen FRUID
                            </h3>
                            <p class="text-xs text-slate-500">Format formulir standar BPR Artha Pamenang</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Dokumen Siap Ditinjau
                        </span>
                    </div>
                </div>

                {{-- Iframe Dokumen dengan Loading State Elegan --}}
                <div class="relative h-[min(76vh,54rem)] min-h-[36rem] bg-slate-100" x-data="{ loaded: false }">
                    <div x-show="!loaded" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-4 bg-slate-50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 shadow-xs">
                            <svg class="h-6 w-6 animate-spin text-brand-600" fill="none" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-slate-800">Menyiapkan Dokumen FRUID</p>
                            <p class="mt-0.5 text-xs text-slate-500">Memuat pratinjau aman sesi Anda…</p>
                        </div>
                    </div>

                    <iframe src="{{ route('dokumen.preview-session') }}" @load="loaded = true"
                        class="relative z-0 h-full w-full border-0 bg-white" title="Pratinjau Dokumen FRUID"></iframe>
                </div>
            </section>

            {{-- ══════════════════ KOLOM KANAN: KONSOL RINGKASAN & VERIFIKASI ══════════════════ --}}
            <aside class="space-y-5 xl:sticky xl:top-4" aria-label="Ringkasan sebelum kirim">

                {{-- ── 1. Kartu Ringkasan Data Utama ── --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-slate-900">Ringkasan Pengajuan</h3>
                    </div>

                    <dl class="mt-3.5 space-y-2.5 text-xs">
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Pemohon</dt>
                            <dd class="font-bold text-slate-900 text-right">{{ $user->name }}</dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">NIK / User ID</dt>
                            <dd class="font-mono font-semibold text-slate-800 text-right">
                                {{ $user->nik }} · <span class="text-brand-700">{{ $permohonan->user_id_ussi }}</span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Jenis Permohonan</dt>
                            <dd class="text-right">
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-700">
                                    {{ $permohonan->jenis_permohonan?->label() ?? 'Pendaftaran' }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                            <dt class="text-slate-500">Hak Akses USSI</dt>
                            <dd class="text-right">
                                <span
                                    class="rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-bold text-brand-700 border border-brand-200">
                                    {{ $permohonan->access_level?->value ?? 'USER' }}
                                </span>
                            </dd>
                        </div>
                        @if ($permohonan->jenis_permohonan?->value === 'perubahan' && $permohonan->jabatan_baru)
                            <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                                <dt class="text-slate-500">Jabatan Tujuan</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $permohonan->jabatan_baru }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-2 py-1">
                            <dt class="text-slate-500">Penyetuju Dokumen</dt>
                            <dd class="font-semibold text-slate-800 text-right">
                                {{ $atasan ? $atasan->name : ($user->isDirutByJabatan() ? 'Langsung Eksekusi IT' : '—') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- ── 2. Kartu Checklist Verifikasi ── --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Pemeriksaan Sebelum Kirim</h3>
                    <ul class="mt-3 space-y-2.5 text-xs text-slate-700">
                        <li class="flex items-start gap-2.5">
                            <svg class="h-4 w-4 flex-shrink-0 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Data pemohon, NIK, dan kantor telah terverifikasi sesuai profil.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="h-4 w-4 flex-shrink-0 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Tingkat hak akses USSI yang dipilih telah sesuai kebutuhan tugas.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="h-4 w-4 flex-shrink-0 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Atasan yang ditunjuk memiliki wewenang untuk menyetujui formulir.</span>
                        </li>
                    </ul>
                </div>

                {{-- ── 3. Alert Peringatan ── --}}
                <div class="rounded-2xl border border-amber-200/80 bg-amber-50/70 p-4 text-xs text-amber-900 shadow-2xs">
                    <div class="flex items-start gap-2.5">
                        <svg class="h-4 w-4 flex-shrink-0 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-bold">Otorisasi Tidak Dapat Dibatalkan</p>
                            <p class="mt-0.5 text-amber-800/90 leading-relaxed">
                                Setelah dikirim, dokumen langsung diteruskan ke atasan dan sistem tidak mengizinkan
                                pengubahan data secara sepihak.
                            </p>
                        </div>
                    </div>
                </div>

            </aside>
        </div>

        {{-- ══════════════════ BILAH AKSI STICKY DI BAWAH ══════════════════ --}}
        <div class="sticky bottom-4 z-20 mt-8">
            <div
                class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200/80 bg-white/95 p-4 shadow-lg backdrop-blur-md">
                <div>
                    <a href="{{ route('permohonan.step2', $step2Parameters) }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-xs transition-colors hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data Permohonan
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden text-xs font-medium text-slate-500 sm:block">
                        Langkah terakhir · siap dikirimkan
                    </span>

                    <form method="POST" action="{{ route('permohonan.submit') }}" x-data="{ loading: false }"
                        @submit="loading = true">
                        @csrf
                        <input type="hidden" name="from_preview" value="1">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm shadow-brand-600/30 transition-all hover:bg-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 disabled:opacity-50"
                            :disabled="loading">
                            <x-spinner x-show="loading" x-cloak />
                            <svg x-show="!loading" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span x-text="loading ? 'Mengirim permohonan…' : 'Kirim Permohonan'">Kirim Permohonan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
