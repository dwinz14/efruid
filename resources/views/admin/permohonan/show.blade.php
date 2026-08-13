@extends('layouts.app')

@section('title', 'Detail Permohonan')
@section('page-title', 'Detail Permohonan — Admin View')

@section('content')
    <div class="max-w-3xl mx-auto space-y-5">

        <div class="card card-body">
            <div class="flex items-start justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-mono text-slate-400 mb-1">
                        {{ $permohonan->nomor_dokumen ?? 'Draft' }}
                    </p>
                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ $permohonan->jenis_permohonan->label() }}
                        — {{ $permohonan->form_type->label() }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Pemohon: <strong>{{ $permohonan->pemohon?->name }}</strong>
                        ({{ $permohonan->nik_pemohon }})
                        &middot; {{ $permohonan->kantor?->label }}
                    </p>
                </div>
                <span class="{{ $permohonan->status->badgeClass() }} text-sm px-3 py-1">
                    {{ $permohonan->status->label() }}
                </span>
            </div>

            @if ($permohonan->alasan_reject)
                <div class="alert-danger mt-4">
                    <span><strong>Alasan Ditolak:</strong> {{ $permohonan->alasan_reject }}</span>
                </div>
            @endif

            @if ($permohonan->revision_count > 0)
                <p class="text-xs text-amber-600 mt-2">
                    Revisi ke-{{ $permohonan->revision_count }}
                </p>
            @endif

            <div class="flex gap-2 mt-4">
                @if ($permohonan->pdf_path)
                    <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank" class="btn-primary btn-sm">
                        Download PDF
                    </a>
                @endif
                <a href="{{ route('admin.permohonan.index') }}" class="btn-ghost btn-sm">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- Preview dokumen --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-800">Dokumen Permohonan</h3>
                <a href="{{ route('dokumen.preview', $permohonan) }}" target="_blank" class="btn-ghost btn-sm">
                    Buka di tab baru
                </a>
            </div>
            <div class="overflow-hidden rounded-b-card" style="height:700px">
                <iframe src="{{ route('dokumen.preview', $permohonan) }}" style="width:100%;height:100%;border:none;"
                    title="Preview Dokumen FRUID">
                </iframe>
            </div>
        </div>

        {{-- Riwayat --}}
        @if ($permohonan->approvalLogs->isNotEmpty())
            @include('approval.partials.approval-log', ['logs' => $permohonan->approvalLogs])
        @endif

    </div>
@endsection
