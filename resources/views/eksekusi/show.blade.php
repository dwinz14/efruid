@extends('layouts.app')

@section('title', 'Eksekusi Permohonan')
@section('page-title', 'Eksekusi Permohonan')

@section('content')
    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Info --}}
        <div class="card card-body">
            <div class="flex items-start justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs text-slate-400 font-mono mb-1">
                        {{ $permohonan->nomor_dokumen }}
                    </p>
                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ $permohonan->jenis_permohonan->label() }}
                        — {{ $permohonan->form_type->label() }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Pemohon: <strong>{{ $permohonan->pemohon?->name }}</strong>
                        &middot; {{ $permohonan->kantor?->label }}
                    </p>
                    @if ($permohonan->revision_count > 0)
                        <p class="text-xs text-amber-600 mt-1">
                            Revisi ke-{{ $permohonan->revision_count }}
                        </p>
                    @endif
                </div>
                <span class="{{ $permohonan->status->badgeClass() }} text-sm px-3 py-1">
                    {{ $permohonan->status->label() }}
                </span>
            </div>

            {{-- Verification stamps yang sudah ada --}}
            @if (!empty($permohonan->verification_stamps))
                <div class="mt-4 pt-4 border-t border-surface-border">
                    <p class="text-xs font-semibold text-slate-500 uppercase
                           tracking-wider mb-2">
                        Verification Record</p>
                    <div class="space-y-2">
                        @foreach ($permohonan->verification_stamps as $stamp)
                            <div
                                class="flex items-start gap-2 text-xs text-slate-600
                                    bg-slate-50 rounded px-3 py-2">
                                <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707
                                                         -9.293a1 1 0 00-1.414-1.414L9 10.586 7.707
                                                         9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414
                                                         0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <span class="font-medium">{{ $stamp['role'] }}</span>:
                                    {{ $stamp['nama'] }} &middot; {{ $stamp['timestamp'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Preview dokumen --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Dokumen Final</h3>
            </div>
            <div class="overflow-x-auto">
                <div class="card">
                    <div class="overflow-hidden rounded-b-card" style="height: 700px;">
                        <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                            style="width:100%;height:100%;border:none;" title="Preview Dokumen FRUID"></iframe>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Eksekusi --}}
        @if ($permohonan->status->value === 'PENDING_IT')

            @php $myId = auth()->id(); @endphp

            @if (!$permohonan->isClaimed())
                {{-- Belum diklaim: tampilkan tombol Ambil --}}
                <div class="card card-body">
                    <h3 class="text-sm font-semibold text-slate-800 mb-1">Ambil Permohonan Ini</h3>
                    <p class="text-sm text-slate-500 mb-4">
                        Klik "Ambil" untuk menandai bahwa Anda yang akan mengeksekusi permohonan ini di sistem USSI.
                        Setelah diambil, anggota tim lain tidak bisa mengeksekusi FRUID yang sama.
                    </p>
                    <form action="{{ route('eksekusi.claim', $permohonan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary">
                            Ambil Permohonan Ini
                        </button>
                    </form>
                </div>
            @elseif ($permohonan->isClaimedBy($myId))
                {{-- Saya yang klaim: tampilkan form eksekusi --}}
                <div class="card card-body" x-data="{ confirm: false, loading: false }">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800 mb-0.5">Eksekusi Permohonan</h3>
                            <p class="text-xs text-slate-400">
                                Diambil sejak {{ $permohonan->claimed_at?->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                            </p>
                        </div>
                        <form action="{{ route('eksekusi.unclaim', $permohonan) }}" method="POST"
                            onsubmit="return confirm('Lepas klaim permohonan ini? Permohonan akan kembali tersedia untuk tim.')">
                            @csrf
                            <button type="submit" class="btn-ghost btn-sm text-slate-400">
                                Lepas Klaim
                            </button>
                        </form>
                    </div>

                    <p class="text-sm text-slate-500 mb-4">
                        Tandai permohonan ini sudah dieksekusi di sistem USSI.
                        Nama Anda akan tercatat sebagai Administrator Aplikasi USSI di dokumen FRUID.
                        PDF akan digenerate otomatis setelah eksekusi.
                    </p>

                    {{-- Step 1: tombol konfirmasi --}}
                    <div x-show="!confirm">
                        <button type="button" @click="confirm = true" class="btn-primary">
                            Tandai Sudah Dieksekusi
                        </button>
                    </div>

                    {{-- Step 2: form konfirmasi --}}
                    <div x-show="confirm" x-transition>
                        <div class="alert-info mb-4">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0
                                             11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001
                                             1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span>
                                Pastikan permohonan <strong>{{ $permohonan->nomor_dokumen }}</strong>
                                sudah benar-benar dieksekusi di sistem USSI sebelum konfirmasi.
                                Nama <strong>{{ auth()->user()->name }}</strong> akan tercatat di dokumen.
                            </span>
                        </div>

                        <form method="POST" action="{{ route('eksekusi.execute', $permohonan) }}"
                            @submit="loading = true">
                            @csrf
                            <div class="mb-4">
                                <label class="label">Catatan Eksekusi (opsional)</label>
                                <input name="catatan" type="text" class="input"
                                    placeholder="Contoh: User ID sudah aktif di USSI" maxlength="255">
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="btn-primary" :disabled="loading">
                                    <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                    <span x-text="loading ? 'Memproses...' : 'Konfirmasi Eksekusi'">
                                        Konfirmasi Eksekusi
                                    </span>
                                </button>
                                <button type="button" @click="confirm = false" class="btn-ghost" :disabled="loading">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                {{-- Diklaim orang lain: hanya info --}}
                <div class="card card-body">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                Sedang dikerjakan oleh {{ $permohonan->executor?->name }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Diambil sejak {{ $permohonan->claimed_at?->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($permohonan->status->value === 'EXECUTED')
            {{-- Sudah dieksekusi --}}
            <div class="card card-body">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <p class="text-sm font-semibold text-green-700">Permohonan sudah dieksekusi</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            @php
                                $execLog = $permohonan->approvalLogs->where('aksi', 'executed')->last();
                            @endphp
                            @if ($execLog)
                                Oleh {{ $execLog->user?->name }}
                                pada {{ $execLog->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                            @endif
                        </p>
                    </div>
                    @if ($permohonan->pdf_path)
                        <a href="{{ route('permohonan.pdf', $permohonan) }}" target="_blank" class="btn-primary btn-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1
                                           1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF
                        </a>
                    @else
                        <span class="text-xs text-slate-400">PDF sedang digenerate...</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Riwayat --}}
        @if ($permohonan->approvalLogs->isNotEmpty())
            @include('approval.partials.approval-log', ['logs' => $permohonan->approvalLogs])
        @endif

        <div class="pb-4">
            <a href="{{ route('eksekusi.index') }}" class="btn-ghost btn-sm">
                ← Kembali ke Daftar
            </a>
        </div>

    </div>
@endsection
