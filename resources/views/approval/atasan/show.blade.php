@extends('layouts.app')

@section('title', 'Proses Permohonan')
@section('page-title', 'Persetujuan Permohonan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- ── 1. Top Navigation & Page Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('approval.atasan.index') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke antrean">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            {{ $permohonan->nomor_dokumen ?? 'Draft Dokumen' }}
                        </span>
                        @if ($permohonan->form_type->value === 'rangkap')
                            <span
                                class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                Rangkap Jabatan
                            </span>
                        @else
                            <span
                                class="text-[10px] font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                                Reguler (Normal)
                            </span>
                        @endif
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight mt-1">
                        Persetujuan Permohonan FRUID
                    </h2>
                </div>
            </div>

            <div class="flex-shrink-0">
                <span
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold border shadow-xs {{ $permohonan->status->badgeClass() }}">
                    <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
                    Status: {{ $permohonan->status->label() }}
                </span>
            </div>
        </div>

        {{-- ── 2. Executive Summary Cards ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 animate-fade-up"
            style="animation-delay: 0.05s;">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Pemohon --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemohon</p>
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($permohonan->pemohon?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $permohonan->pemohon?->name ?? '—' }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">{{ $permohonan->pemohon?->jabatan_label ?? 'Staff' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Kantor --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kantor Cabang</p>
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800 mt-1">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span
                            class="truncate">{{ $permohonan->kantor?->label ?? ($permohonan->kantor?->nama ?? '—') }}</span>
                    </div>
                </div>

                {{-- Jenis Permohonan --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jenis Permohonan</p>
                    <div class="mt-1">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $permohonan->jenis_permohonan->label() }}
                        </span>
                    </div>
                </div>

                {{-- Tanggal Pengajuan --}}
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800 mt-1">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $permohonan->tanggal_permohonan?->locale('id')->isoFormat('D MMMM Y') ?? $permohonan->created_at->locale('id')->isoFormat('D MMMM Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 3. Dokumen Permohonan Viewer (A4 Sheet Preview) ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.1s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Lembar Dokumen Permohonan (FRUID)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tinjau seluruh data dan hak akses yang dimohonkan</p>
                    </div>
                </div>
            </div>

            {{-- Canvas Viewer Frame --}}
            <div class="bg-slate-100/80 p-3 sm:p-5">
                <div class="w-full bg-white shadow-xl rounded-xl border border-slate-200/80 overflow-hidden"
                    style="height: 740px;">
                    <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                        style="width: 100%; height: 100%; border: none;" title="Preview Dokumen FRUID"
                        sandbox="allow-same-origin allow-scripts allow-popups">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- ── 4. Action Console: Keputusan Atasan ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.15s;" x-data="approvalAction()">

            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Tindakan Persetujuan Atasan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tentukan keputusan anda terhadap permohonan ini</p>
                    </div>
                </div>
            </div>

            <div class="p-5 sm:p-6 space-y-5">
                {{-- Pilihan Aksi: Setujui vs Tolak --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    {{-- Tombol Pilihan Setujui --}}
                    <button type="button" @click="setAction('approve')"
                        class="p-4 rounded-xl border-2 flex items-center gap-3.5 transition-all text-left focus:outline-none"
                        :class="action === 'approve'
                            ?
                            'border-emerald-500 bg-emerald-50/70 shadow-sm shadow-emerald-500/10' :
                            'border-slate-200 hover:border-slate-300 hover:bg-emerald-100'">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="action === 'approve' ? 'bg-emerald-600 text-white shadow-xs' :
                                'bg-slate-100 text-slate-500'">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold"
                                :class="action === 'approve' ? 'text-emerald-900' : 'text-slate-800'">
                                Setujui Permohonan
                            </p>
                            <p class="text-xs mt-0.5"
                                :class="action === 'approve' ? 'text-emerald-700' : 'text-slate-500'">
                                Teruskan dokumen ke tahap berikutnya
                            </p>
                        </div>
                    </button>

                    {{-- Tombol Pilihan Tolak --}}
                    <button type="button" @click="setAction('reject')"
                        class="p-4 rounded-xl border-2 flex items-center gap-3.5 transition-all text-left focus:outline-none"
                        :class="action === 'reject'
                            ?
                            'border-red-500 bg-red-50/70 shadow-sm shadow-red-500/10' :
                            'border-slate-200 hover:border-slate-300 hover:bg-red-300'">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="action === 'reject' ? 'bg-red-600 text-white shadow-xs' : 'bg-slate-100 text-slate-500'">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold"
                                :class="action === 'reject' ? 'text-red-900' : 'text-slate-800'">
                                Tolak Permohonan
                            </p>
                            <p class="text-xs mt-0.5" :class="action === 'reject' ? 'text-red-700' : 'text-slate-500'">
                                Batalkan dan berikan alasan penolakan
                            </p>
                        </div>
                    </button>
                </div>

                {{-- ── Form Detail Persetujuan (Approve) ── --}}
                <div x-show="showApprove" x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-4 pt-2 border-t border-slate-100">

                    <div
                        class="p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3.5 text-emerald-800">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="text-xs space-y-1">
                            <p class="font-bold text-emerald-900 text-sm">Konfirmasi Persetujuan Atasan</p>
                            <p class="leading-relaxed">
                                Dengan menyetujui permohonan ini, personal digital seal anda akan otomatis disematkan pada
                                dokumen FRUID dan status akan diteruskan ke tahap
                                @if ($permohonan->form_type->value === 'rangkap')
                                    <span class="font-bold text-emerald-900">Persetujuan Direktur Utama</span>.
                                @else
                                    <span class="font-bold text-emerald-900">Eksekusi IT</span>.
                                @endif
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('approval.atasan.approve', $permohonan) }}"
                        @submit="loading = true">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span x-text="loading ? 'Memproses Persetujuan...' : 'Konfirmasi & Setujui Permohonan'"></span>
                        </button>
                    </form>
                </div>

                {{-- ── Form Detail Penolakan (Reject) ── --}}
                <div x-show="showReject" x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-4 pt-2 border-t border-slate-100">

                    <div class="p-4 rounded-xl bg-red-50 border border-red-200/80 flex items-start gap-3.5 text-red-800">
                        <div
                            class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="text-xs space-y-1">
                            <p class="font-bold text-red-900 text-sm">Konfirmasi Penolakan</p>
                            <p class="leading-relaxed">
                                Permohonan ini akan ditolak dan dihentikan. Pemohon akan menerima notifikasi beserta alasan
                                penolakan yang anda berikan.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('approval.atasan.reject', $permohonan) }}"
                        @submit="loading = true">
                        @csrf
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-slate-700">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <span class="text-xs font-mono font-medium"
                                    :class="alasan.length < 10 ? 'text-amber-600 font-bold' : 'text-slate-400'"
                                    x-text="alasan.length + ' / 500 karakter (min. 10)'"></span>
                            </div>
                            <textarea name="alasan_reject" rows="3" class="input w-full @error('alasan_reject') input-error @enderror"
                                placeholder="Tuliskan secara jelas alasan penolakan permohonan ini agar pemohon dapat mengetahuinya..."
                                x-model="alasan" required minlength="10" maxlength="500"></textarea>
                            @error('alasan_reject')
                                <p class="field-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="mt-3 w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading || alasan.trim().length < 10">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span x-text="loading ? 'Memproses Penolakan...' : 'Konfirmasi Tolak Permohonan'"></span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Footer Link --}}
            <div class="px-5 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <a href="{{ route('approval.atasan.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Antrean Approval
                </a>
                <span class="text-xs text-slate-400">Pastikan seluruh data telah sesuai sebelum memberi keputusan</span>
            </div>
        </div>

        {{-- ── 5. Riwayat Approval / Audit Trail ── --}}
        @if ($permohonan->approvalLogs->isNotEmpty())
            @include('approval.partials.approval-log', ['logs' => $permohonan->approvalLogs])
        @endif

    </div>

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
