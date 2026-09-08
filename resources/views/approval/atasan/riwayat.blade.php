@extends('layouts.app')

@section('title', 'Riwayat Approval Saya')
@section('page-title', 'Riwayat Approval')

@section('content')
    <div class="space-y-6">

        {{-- ── Top Navigation & Page Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-brand-50 rounded-xl border border-brand-100 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Riwayat Persetujuan Saya</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Daftar seluruh permohonan FRUID yang pernah Anda setujui atau tolak
                    </p>
                </div>
            </div>

            {{-- Quick Link ke Pending --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('approval.atasan.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 shadow-sm hover:bg-slate-50 hover:text-brand-600 hover:border-brand-200 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Antrean Pending</span>
                </a>
            </div>
        </div>

        {{-- ── Metric Stat Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade-up" style="animation-delay: 0.05s;">
            {{-- Total Diproses --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Diproses</p>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $riwayat->total() }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Permohonan diselesaikan</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 text-slate-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            {{-- Disetujui --}}
            @php
                $approvedCount = $riwayat
                    ->filter(fn($item) => $item->approvalLogs->first()?->aksi === 'approved')
                    ->count();
                $rejectedCount = $riwayat
                    ->filter(fn($item) => $item->approvalLogs->first()?->aksi === 'rejected')
                    ->count();
            @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Disetujui (Halaman Ini)</p>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $approvedCount }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Diteruskan ke tahap berikutnya</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            {{-- Ditolak --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Ditolak (Halaman Ini)</p>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 mt-1">{{ $rejectedCount }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Dikembalikan atau dibatalkan</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── Container Tabel Riwayat ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.1s;">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Daftar Arsip Persetujuan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Klik tombol Lihat Dokumen untuk pratinjau lembar FRUID</p>
                </div>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ $riwayat->total() }} Data
                </span>
            </div>

            @if ($riwayat->isEmpty())
                {{-- Empty State --}}
                <div class="px-6 py-16 flex flex-col items-center justify-center text-center bg-white">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-1">Belum Ada Riwayat</h4>
                    <p class="text-sm text-slate-500 max-w-sm">
                        Permohonan yang telah Anda setujui atau tolak akan diarsipkan dan muncul di halaman ini.
                    </p>
                    <a href="{{ route('approval.atasan.index') }}"
                        class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-brand-600 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm hover:bg-brand-700 transition-colors">
                        Periksa Permohonan Pending
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @else
                {{-- Table Area --}}
                <div class="overflow-x-auto custom-scrollbar w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50/80 border-b border-slate-100 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                                <th class="px-5 sm:px-6 py-3.5">Dokumen & Form</th>
                                <th class="px-5 sm:px-6 py-3.5">Pemohon & Kantor</th>
                                <th class="px-5 sm:px-6 py-3.5">Jenis</th>
                                <th class="px-5 sm:px-6 py-3.5 text-center">Keputusan Saya</th>
                                <th class="px-5 sm:px-6 py-3.5">Status Akhir</th>
                                <th class="px-5 sm:px-6 py-3.5">Waktu Aksi</th>
                                <th class="px-5 sm:px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @foreach ($riwayat as $item)
                                @php
                                    $log = $item->approvalLogs->first();
                                    $isApproved = $log?->aksi === 'approved';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">

                                    {{-- Kolom Dokumen --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200/80 group-hover:border-slate-300">
                                                {{ $item->nomor_dokumen ?? '—' }}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center gap-1.5">
                                            @if ($item->form_type->value === 'rangkap')
                                                <span
                                                    class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 px-2 py-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Rangkap Jabatan
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 text-[10px] font-semibold text-brand-700 px-2 py-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                                    Reguler (Normal)
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Kolom Pemohon --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-brand-100 border border-brand-200 text-brand-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                                {{ strtoupper(substr($item->pemohon?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-slate-900 truncate">
                                                    {{ $item->pemohon?->name ?? '—' }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5 truncate">
                                                    {{ $item->kantor?->nama ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Jenis Permohonan --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/60">
                                            {{ $item->jenis_permohonan->label() }}
                                        </span>
                                    </td>

                                    {{-- Kolom Keputusan Saya --}}
                                    <td class="px-5 sm:px-6 py-4 text-center">
                                        @if ($isApproved)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Disetujui
                                            </span>
                                        @elseif ($log?->aksi === 'rejected')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200 shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-red-600" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Status Akhir --}}
                                    <td class="px-5 sm:px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->status->badgeClass() }}">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>

                                    {{-- Kolom Waktu Aksi --}}
                                    <td class="px-5 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs font-medium text-slate-700">
                                            {{ $log?->created_at->locale('id')->isoFormat('D MMM Y') ?? '—' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $log?->created_at->locale('id')->isoFormat('HH:mm') ?? '' }} WIB
                                        </div>
                                    </td>

                                    {{-- Kolom Aksi / Lihat Modal --}}
                                    <td class="px-5 sm:px-6 py-4 text-right">
                                        <button type="button"
                                            @click="$dispatch('buka-dokumen-modal', {
                                                    previewUrl:    '{{ route('dokumen.preview', $item) }}',
                                                    nomorDokumen:  '{{ $item->nomor_dokumen }}',
                                                    pemohon:       '{{ addslashes($item->pemohon?->name ?? '—') }}',
                                                    status:        '{{ $item->status->label() }}',
                                                    statusClass:   '{{ $item->status->badgeClass() }}'
                                                })"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 hover:text-brand-700 border border-brand-200/80 transition-all shadow-xs active:scale-95"
                                            title="Lihat Dokumen FRUID">
                                            <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Lihat</span>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if ($riwayat->hasPages())
                    <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $riwayat->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>

    {{-- Pop-up Modal Preview Dokumen FRUID --}}
    @include('approval.partials.dokumen-modal')

@endsection
