@extends('layouts.app')

@section('title', 'Detail Permohonan')
@section('page-title', 'Detail Permohonan')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Top Action Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('permohonan.index') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/60 shadow-sm text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-all focus:ring-2 focus:ring-slate-200 active:scale-95 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Detail Permohonan</h2>
                    <p class="text-sm text-slate-500 mt-0.5 font-mono font-medium">
                        {{ $permohonan->nomor_dokumen ?? '— Menunggu Nomor (Draft) —' }}
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-2">
                @if ($permohonan->isDraft())
                    <a href="{{ route('permohonan.edit', $permohonan) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-brand-50 text-brand-700 text-sm font-semibold rounded-xl hover:bg-brand-100 transition-colors focus:ring-2 focus:ring-brand-500 focus:ring-offset-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Draft
                    </a>
                @endif

                @if ($permohonan->isRevisable())
                    <form method="POST" action="{{ route('permohonan.revise', $permohonan) }}"
                        onsubmit="return confirm('Kembalikan ke draft untuk direvisi?')" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 shadow-sm transition-colors focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Revisi
                        </button>
                    </form>
                @endif

                @if ($permohonan->isCancellable())
                    <form method="POST" action="{{ route('permohonan.cancel', $permohonan) }}"
                        onsubmit="return confirm('Yakin ingin membatalkan permohonan ini?')" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-100 hover:text-red-700 transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batalkan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Alasan Reject --}}
        @if ($permohonan->alasan_reject)
            <div class="flex items-start gap-4 p-5 rounded-2xl bg-red-50 border-l-4 border-red-500 shadow-sm animate-fade-up"
                style="animation-delay: 0.1s;" role="alert">
                <div class="bg-red-100/80 p-2.5 rounded-full flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <h3 class="text-sm font-bold text-red-800">Permohonan Ditolak / Dikembalikan</h3>
                    <p class="text-sm text-red-700 mt-1 leading-relaxed">{{ $permohonan->alasan_reject }}</p>
                </div>
            </div>
        @endif

        {{-- Kartu Info Utama --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm animate-fade-up grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
            style="animation-delay: 0.15s;">
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jenis Permohonan</p>
                <p class="text-sm font-semibold text-slate-800">{{ $permohonan->jenis_permohonan->label() }}</p>
                <p class="text-xs font-medium text-slate-500">{{ $permohonan->form_type->label() }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kantor Cabang</p>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-800">{{ $permohonan->kantor?->nama ?? '—' }}</p>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMMM Y') ?? '—' }}</p>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Terkini</p>
                <div class="mt-1">
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $permohonan->status->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $permohonan->status->label() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Dokumen Preview --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.2s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-200/50">
                        <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Dokumen Permohonan</h3>
                </div>

                @if ($permohonan->pdf_path)
                    <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 hover:text-brand-600 shadow-sm transition-colors focus:ring-2 focus:ring-brand-500 focus:ring-offset-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PDF
                    </a>
                @elseif($permohonan->status->value === 'EXECUTED')
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs font-semibold border border-amber-200/60">
                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                        PDF sedang digenerate...
                    </div>
                @endif
            </div>

            <div class="bg-slate-100/50 p-4 sm:p-6 lg:p-8 overflow-x-auto">
                <div class="mx-auto bg-white rounded-xl shadow-[0_0_15px_rgba(0,0,0,0.05)] border border-slate-200/60 overflow-hidden"
                    style="max-width: 850px;">
                    <div class="w-full h-[700px] lg:h-[900px] relative">
                        <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                            class="absolute inset-0 w-full h-full border-none bg-white" title="Preview Dokumen FRUID">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Status (Timeline) --}}
        @if ($permohonan->approvalLogs->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden animate-fade-up"
                style="animation-delay: 0.3s;">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Riwayat Perjalanan Dokumen</h3>
                </div>

                <div class="p-6 sm:p-8">
                    <div
                        class="relative space-y-6 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">

                        @foreach ($permohonan->approvalLogs as $index => $log)
                            <div
                                class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                {{-- Icon / Bullet --}}
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-slate-100 text-slate-500 shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors duration-300">
                                    @if ($loop->first)
                                        <svg class="w-4 h-4 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                    @endif
                                </div>

                                {{-- Card Content --}}
                                <div
                                    class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl border border-slate-100 shadow-sm transition-shadow hover:shadow-md">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <span
                                                class="text-sm font-bold text-slate-800">{{ $log->user?->name ?? 'Sistem / Otomatis' }}</span>
                                            <span
                                                class="text-[11px] font-medium text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md whitespace-nowrap">
                                                {{ $log->created_at->locale('id')->isoFormat('D MMM Y HH:mm') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500">
                                            Melakukan <span
                                                class="font-semibold text-slate-700 capitalize">{{ $log->aksi }}</span>:
                                            <span class="inline-block mt-1">
                                                <span class="text-slate-400">{{ $log->status_dari }}</span>
                                                <span class="mx-1 text-slate-300">&rarr;</span>
                                                <span class="font-medium text-brand-600">{{ $log->status_ke }}</span>
                                            </span>
                                        </p>
                                        @if ($log->catatan)
                                            <div class="mt-2.5 p-2.5 bg-amber-50/50 border border-amber-100 rounded-lg">
                                                <p class="text-xs text-amber-800 italic leading-relaxed">
                                                    "{{ $log->catatan }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
