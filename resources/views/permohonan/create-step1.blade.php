@extends('layouts.app')

@section('title', 'Buat Permohonan')
@section('page-title', 'Buat Permohonan')
@section('width', 'md')

@section('content')
    @php
        // Satu sumber data untuk kedua kartu (tanpa markup ganda).
        $options = [
            [
                'key' => \App\Enums\FormType::NORMAL->value,
                'title' => 'Tidak Rangkap Jabatan',
                'description' => 'Untuk karyawan dengan satu penugasan.',
                'flow' => ['Pemohon', 'Atasan'],
                'signers' => 2,
                'badge' => 'Paling umum',
            ],
            [
                'key' => \App\Enums\FormType::RANGKAP->value,
                'title' => 'Rangkap Jabatan',
                'description' => 'Untuk karyawan yang merangkap dua penugasan sekaligus.',
                'flow' => ['Pemohon', 'Atasan', 'Dirut'],
                'signers' => 3,
                'badge' => null,
            ],
        ];
    @endphp

    <div class="space-y-6">

        {{-- Stepper --}}
        @include('permohonan.partials.stepper', ['step' => 1])

        {{-- Judul langkah --}}
        <div class="border-b border-slate-200 pb-5">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">Permohonan baru</p>
            <div class="mt-2 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Pilih format permohonan</h2>
                    <p class="mt-1.5 max-w-xl text-sm leading-6 text-slate-600">
                        Tentukan format berdasarkan penugasan Anda. Alur persetujuan ditetapkan otomatis setelah pilihan dibuat.
                    </p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Sekitar 3 menit
                </span>
            </div>
        </div>

        {{-- Pilihan form: kartu berupa tautan, satu klik langsung lanjut --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($options as $opt)
                <a href="{{ route('permohonan.step2', ['form_type' => $opt['key']]) }}"
                    aria-labelledby="form-{{ $opt['key'] }}-title" aria-describedby="form-{{ $opt['key'] }}-desc"
                    class="group flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition duration-150 hover:-translate-y-0.5 hover:border-brand-400 hover:shadow-card-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">

                    <div class="flex items-start justify-between gap-3">
                        <p id="form-{{ $opt['key'] }}-title" class="text-base font-semibold text-slate-900">
                            {{ $opt['title'] }}
                        </p>
                        @if ($opt['badge'])
                            <span
                                class="flex-shrink-0 rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">
                                {{ $opt['badge'] }}
                            </span>
                        @endif
                    </div>

                    <div id="form-{{ $opt['key'] }}-desc" class="mt-1 flex-1">
                        <p class="text-sm text-slate-600">{{ $opt['description'] }}</p>

                        <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Alur persetujuan
                        </p>
                        <ol class="mt-1.5 flex flex-wrap items-center gap-1.5">
                            @foreach ($opt['flow'] as $role)
                                <li class="flex items-center gap-1.5">
                                    <span
                                        class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-sm font-medium text-slate-700">
                                        {{ $role }}
                                    </span>
                                    @unless ($loop->last)
                                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    @endunless
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-sm text-slate-500">{{ $opt['signers'] }} penandatangan</span>
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-brand-700">
                            Pilih
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Bantuan --}}
        <x-alert type="info" title="Sebelum melanjutkan">
            Setelah disetujui, permohonan diteruskan ke tim IT untuk dieksekusi. Jika format belum jelas, konsultasikan
            dengan atasan atau tim IT terlebih dahulu.
        </x-alert>

        {{-- Kembali --}}
        <a href="{{ route('permohonan.index') }}" class="btn btn-ghost -ml-3 min-h-11">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
            </svg>
            Kembali
        </a>
    </div>
@endsection
