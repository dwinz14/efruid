@extends('layouts.app')

@section('title', 'Pilih Format Permohonan')
@section('page-title', 'Buat Permohonan')
@section('width', 'lg')

@section('content')
    @php
        $options = [
            [
                'key' => \App\Enums\FormType::NORMAL->value,
                'title' => 'Tidak Rangkap Jabatan',
                'subtitle' => 'Penugasan Standar / Tunggal',
                'description' => 'Untuk karyawan dengan satu penugasan pada satu unit kerja / kantor.',
                'flow' => ['Pemohon', 'Atasan Langsung', 'Eksekusi IT'],
                'signers' => '2 Penandatangan',
                'badge' => 'Format Standar',
                'badge_color' => 'bg-brand-50 text-brand-700 border-brand-200',
                'theme' => 'brand',
            ],
            [
                'key' => \App\Enums\FormType::RANGKAP->value,
                'title' => 'Rangkap Jabatan',
                'subtitle' => 'Penugasan Khusus / Ganda',
                'description' => 'Untuk karyawan yang mengemban dua tugas sekaligus di kunit kerja / kantor.',
                'flow' => ['Pemohon', 'Atasan', 'Direktur Utama', 'Eksekusi IT'],
                'signers' => '3 Penandatangan',
                'badge' => 'Persetujuan Dirut',
                'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
                'theme' => 'amber',
            ],
        ];
    @endphp

    <div class="space-y-6">

        {{-- Stepper Navigation --}}
        @include('permohonan.partials.stepper', ['step' => 1])

        {{-- Header & Petunjuk Awal --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700 border border-brand-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                            Langkah 01 / 03
                        </span>
                        <span class="text-xs text-slate-400">·</span>
                        <span class="text-xs font-medium text-slate-500">Tahap Inisiasi</span>
                    </div>
                    <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                        Pilih Format Formulir FRUID
                    </h2>
                    <p class="mt-1 text-sm text-slate-600 leading-relaxed">
                        Tentukan format berdasarkan penugasan anda. Sistem akan secara otomatis mengonfigurasi formulir dan
                        alur otorisasi persetujuan dokumen.
                    </p>
                </div>
            </div>
        </div>

        {{-- Kartu Pilihan Format Form --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach ($options as $opt)
                @php
                    $isNormal = $opt['key'] === \App\Enums\FormType::NORMAL->value;
                @endphp
                <a href="{{ route('permohonan.step2', ['form_type' => $opt['key']]) }}" id="card-form-{{ $opt['key'] }}"
                    aria-labelledby="form-{{ $opt['key'] }}-title" aria-describedby="form-{{ $opt['key'] }}-desc"
                    class="group relative flex flex-col justify-between rounded-2xl border-2 bg-white p-6 shadow-sm transition-all duration-200
                        {{ $isNormal ? 'border-slate-200 hover:border-brand-500 hover:shadow-card-hover' : 'border-slate-200 hover:border-amber-500 hover:shadow-card-hover' }}
                        hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">

                    <div>
                        {{-- Top Bar of Card --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-xl transition-colors duration-200
                                    {{ $isNormal ? 'bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white' : 'bg-amber-50 text-amber-700 group-hover:bg-amber-600 group-hover:text-white' }}">
                                    @if ($isNormal)
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @endif
                                </span>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        {{ $opt['subtitle'] }}
                                    </p>
                                    <h3 id="form-{{ $opt['key'] }}-title"
                                        class="text-lg font-bold text-slate-900 group-hover:text-brand-900 transition-colors">
                                        {{ $opt['title'] }}
                                    </h3>
                                </div>
                            </div>

                            @if ($opt['badge'])
                                <span
                                    class="rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $opt['badge_color'] }}">
                                    {{ $opt['badge'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Deskripsi --}}
                        <div id="form-{{ $opt['key'] }}-desc" class="mt-4">
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $opt['description'] }}
                            </p>

                            {{-- Alur Persetujuan Step-by-Step --}}
                            <div class="mt-5 rounded-xl bg-slate-50 border border-slate-200/70 p-3.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Alur Otorisasi Dokumen
                                </p>
                                <ol class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                    @foreach ($opt['flow'] as $role)
                                        <li class="flex items-center gap-1.5">
                                            <span
                                                class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-semibold text-slate-700 shadow-2xs">
                                                {{ $role }}
                                            </span>
                                            @unless ($loop->last)
                                                <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            @endunless
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- Action Footer --}}
                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            {{ $opt['signers'] }}
                        </span>

                        <span
                            class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-1.5 text-sm font-bold transition-all duration-200
                            {{ $isNormal ? 'bg-brand-50 text-brand-700 group-hover:bg-brand-600 group-hover:text-white' : 'bg-amber-50 text-amber-800 group-hover:bg-amber-600 group-hover:text-white' }}">
                            Pilih Format Ini
                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Bantuan & Panduan Memilih --}}
        <div class="rounded-2xl border border-blue-200/80 bg-blue-50/60 p-5 text-slate-700 shadow-xs">
            <div class="flex items-start gap-3.5">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-blue-950">Panduan Memilih Format</h4>
                    <p class="mt-1 text-xs text-blue-900/80 leading-relaxed">
                        Jika anda ragu memilih format, konsultasikan dengan atasan atau tim IT. Setelah dipilih dan
                        disimpan, format penugasan tidak dapat diganti
                        secara langsung pada draft yang sama.
                    </p>
                </div>
            </div>
        </div>

        {{-- Navigasi Bawah --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('permohonan.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Permohonan
            </a>

            <span class="text-xs text-slate-400">
                Langkah 1 dari 3
            </span>
        </div>
    </div>
@endsection
