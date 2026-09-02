@extends('layouts.app')

@section('title', 'Proses Permohonan — Direktur')
@section('page-title', 'Proses Permohonan')

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
                    @if ($permohonan->atasan_id && $permohonan->status->value === 'PENDING_DIRUT')
                        <p class="text-sm text-slate-500">
                            Disetujui Atasan:
                            <strong>{{ $permohonan->atasan?->name }}</strong>
                        </p>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="{{ $permohonan->status->badgeClass() }} text-sm px-3 py-1">
                        {{ $permohonan->status->label() }}
                    </span>
                    {{-- Konteks: kenapa permohonan ini muncul di dashboard Dirut --}}
                    @if ($canApproveAsAtasan)
                        <span class="badge bg-purple-100 text-purple-700 text-xs">
                            Anda adalah atasan dari pemohon ini
                        </span>
                    @elseif($canApproveAsDirut)
                        <span class="badge bg-blue-100 text-blue-700 text-xs">
                            Menunggu persetujuan Direktur Utama
                        </span>
                    @endif
                </div>
            </div>

            @if (!$permohonan->ttd_pemohon_path)
                <div class="alert-warning mt-4">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75
                                 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743
                                 -2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1
                                 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Pemohon belum memiliki tanda tangan digital.</span>
                </div>
            @endif
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

        {{-- Action panel --}}
        @if ($canApproveAsAtasan || $canApproveAsDirut)
            <div class="card card-body" x-data="approvalAction()">

                <script>
                    function approvalAction() {
                        return {
                            action: '',
                            alasan: '',
                            loading: false,
                            setAction(val) {
                                this.action = val;
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

                <h3 class="text-sm font-semibold text-slate-800 mb-1">Keputusan Direktur</h3>
                <p class="text-sm text-slate-500 mb-4">
                    @if ($canApproveAsAtasan)
                        Anda adalah atasan langsung dari pemohon ini.
                        Persetujuan Anda akan meneruskan permohonan langsung ke IT.
                    @else
                        Permohonan rangkap jabatan ini memerlukan persetujuan Direktur Utama.
                    @endif
                </p>

                <div class="grid grid-cols-2 gap-3 mb-5">
                    <button type="button" @click="setAction('approve')" class="approval-btn-choice"
                        :class="action === 'approve'
                            ?
                            'approval-btn-approve-active' :
                            'approval-btn-approve'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium">Setujui</span>
                    </button>
                    <button type="button" @click="setAction('reject')" class="approval-btn-choice"
                        :class="action === 'reject'
                            ?
                            'approval-btn-reject-active' :
                            'approval-btn-reject'">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="font-medium">Tolak</span>
                    </button>
                </div>

                {{-- Approve --}}
                <div x-show="showApprove" x-transition>
                    <div class="alert-info mb-4">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0
                                     1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100
                                     -2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span>
                            Tanda tangan Anda akan diembedkan ke dokumen.
                            @if (!auth()->user()->signature_path)
                                <strong class="text-amber-700">
                                    Anda belum upload tanda tangan.
                                </strong>
                            @endif
                        </span>
                    </div>

                    {{-- Route berbeda tergantung konteks --}}
                    @if ($canApproveAsAtasan)
                        <form method="POST" action="{{ route('approval.dirut.approveAsAtasan', $permohonan) }}"
                            @submit="loading = true">
                            @csrf
                            <button type="submit" class="btn-primary w-full" :disabled="loading">
                                <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span x-text="loading ? 'Memproses...' : 'Konfirmasi Setujui'">
                                    Konfirmasi Setujui
                                </span>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('approval.dirut.approve', $permohonan) }}"
                            @submit="loading = true">
                            @csrf
                            <button type="submit" class="btn-primary w-full" :disabled="loading">
                                <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span x-text="loading ? 'Memproses...' : 'Konfirmasi Setujui'">
                                    Konfirmasi Setujui
                                </span>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Reject --}}
                <div x-show="showReject" x-transition>
                    <form method="POST" action="{{ route('approval.dirut.reject', $permohonan) }}"
                        @submit="loading = true">
                        @csrf
                        <div class="mb-4">
                            <label class="label label-required">Alasan Penolakan</label>
                            <textarea name="alasan_reject" rows="3" class="input @error('alasan_reject') input-error @enderror"
                                placeholder="Jelaskan alasan penolakan (min. 10 karakter)" x-model="alasan" required minlength="10">
                        </textarea>
                            <div class="flex justify-between mt-1">
                                @error('alasan_reject')
                                    <p class="field-error">{{ $message }}</p>
                                @else
                                    <span></span>
                                @enderror
                                <span class="text-xs text-slate-400" x-text="alasan.length + '/500'"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn-danger w-full" :disabled="loading || alasan.length < 10">
                            <span x-text="loading ? 'Memproses...' : 'Konfirmasi Tolak'">
                                Konfirmasi Tolak
                            </span>
                        </button>
                    </form>
                </div>

                <div class="mt-4 pt-4 border-t border-surface-border">
                    <a href="{{ route('approval.dirut.index') }}" class="btn-ghost btn-sm">
                        ← Kembali ke Daftar
                    </a>
                </div>
            </div>
        @endif

        @if ($permohonan->approvalLogs->isNotEmpty())
            @include('approval.partials.approval-log', ['logs' => $permohonan->approvalLogs])
        @endif

    </div>
@endsection
