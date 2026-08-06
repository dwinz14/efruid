@extends('layouts.app')

@section('title', 'Detail Permohonan')
@section('page-title', 'Detail Permohonan')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Header info --}}
    <div class="card card-body">
        <div class="flex items-start justify-between flex-wrap gap-3">
            <div>
                <p class="text-xs text-slate-400 font-mono mb-1">
                    {{ $permohonan->nomor_dokumen ?? 'Belum ada nomor dokumen (Draft)' }}
                </p>
                <h2 class="text-lg font-semibold text-slate-900">
                    {{ $permohonan->jenis_permohonan->label() }} — {{ $permohonan->form_type->label() }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $permohonan->kantor?->label }} &middot;
                    {{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMMM Y') }}
                </p>
            </div>
            <span class="{{ $permohonan->status->badgeClass() }} text-sm px-3 py-1">
                {{ $permohonan->status->label() }}
            </span>
        </div>

        {{-- Alasan reject jika ada --}}
        @if($permohonan->alasan_reject)
            <div class="alert-danger mt-4">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-medium">Permohonan Ditolak</p>
                    <p class="text-sm mt-0.5">{{ $permohonan->alasan_reject }}</p>
                </div>
            </div>
        @endif

        {{-- Aksi --}}
        <div class="flex gap-2 mt-4 flex-wrap">
            @if($permohonan->isDraft())
                <a href="{{ route('permohonan.edit', $permohonan) }}" class="btn-primary btn-sm">Edit Draft</a>
            @endif
            @if($permohonan->isCancellable())
                <form method="POST" action="{{ route('permohonan.cancel', $permohonan) }}"
                    onsubmit="return confirm('Yakin ingin membatalkan permohonan ini?')">
                    @csrf
                    <button type="submit" class="btn-danger btn-sm">Batalkan</button>
                </form>
            @endif
            @if($permohonan->pdf_path)
                <a href="{{ route('permohonan.pdf', $permohonan) }}" class="btn-secondary btn-sm" target="_blank">
                    Download PDF
                </a>
            @endif
            <a href="{{ route('permohonan.index') }}" class="btn-ghost btn-sm">← Kembali</a>
        </div>
    </div>

    {{-- Preview dokumen --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-semibold text-slate-800">Dokumen Permohonan</h3>
        </div>
        <div class="overflow-x-auto">
            @include('permohonan.partials.document-preview', ['p' => $permohonan])
        </div>
    </div>

    {{-- Riwayat status --}}
    @if($permohonan->approvalLogs->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Riwayat Permohonan</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($permohonan->approvalLogs as $log)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-medium text-slate-800">{{ $log->user?->name ?? 'Sistem' }}</p>
                                    <span class="text-xs text-slate-400">{{ $log->created_at->locale('id')->isoFormat('D MMM Y HH:mm') }}</span>
                                </div>
                                <p class="text-sm text-slate-600 mt-0.5 capitalize">
                                    {{ $log->aksi }}:
                                    <span class="text-slate-500">{{ $log->status_dari }}</span>
                                    →
                                    <span class="font-medium text-slate-700">{{ $log->status_ke }}</span>
                                </p>
                                @if($log->catatan)
                                    <p class="text-sm text-slate-500 mt-1 italic">"{{ $log->catatan }}"</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
