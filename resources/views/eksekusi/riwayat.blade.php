@extends('layouts.app')

@section('title', 'Riwayat Eksekusi Saya')
@section('page-title', 'Riwayat Eksekusi IT')

@section('content')
    <div class="space-y-6">

        {{-- ── 1. Page Header & Navigation ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span
                        class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Riwayat Eksekusi Saya
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Daftar seluruh permohonan FRUID yang telah berhasil anda eksekusi dan konfigurasi di sistem USSI.
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('eksekusi.index') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/80 rounded-xl shadow-xs transition-all hover:border-slate-300">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Antrean Pending Eksekusi
                </a>
            </div>
        </div>

        {{-- ── 2. Metric Banner Card ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 relative overflow-hidden group animate-fade-up"
            style="animation-delay: 0.05s;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total FRUID Dieksekusi</p>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $riwayat->total() }}</h3>
                            <span class="text-xs text-slate-500 font-medium">dokumen selesai</span>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block text-right">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Administrator USSI
                    </span>
                    <p class="text-[11px] text-slate-400 mt-1">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
        </div>

        {{-- ── 3. Table of Executed Requests ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.1s;">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Daftar Dokumen Selesai</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Seluruh jejak eksekusi terekam dalam audit log dan PDF dokumen
                    </p>
                </div>

                <span
                    class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                    Total {{ $riwayat->total() }} data
                </span>
            </div>

            @if ($riwayat->isEmpty())
                <div class="p-12 text-center">
                    <div
                        class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">Belum Ada Riwayat Eksekusi</h4>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        Permohonan FRUID yang anda eksekusi di sistem USSI akan otomatis tercatat dan muncul di halaman ini.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('eksekusi.index') }}" class="btn-primary btn-sm">
                            Lihat Antrean Pending Eksekusi
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-slate-50/80 border-b border-slate-200 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5">Nomor Dokumen</th>
                                <th class="px-4 py-3.5">Pemohon</th>
                                <th class="px-4 py-3.5">Kantor Cabang</th>
                                <th class="px-4 py-3.5">Jenis Akses</th>
                                <th class="px-4 py-3.5">Waktu Eksekusi</th>
                                <th class="px-4 py-3.5">File PDF</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($riwayat as $item)
                                <tr class="transition-colors hover:bg-slate-50/80">
                                    {{-- Dokumen --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                                {{ $item->nomor_dokumen }}
                                            </span>
                                            @if ($item->form_type->value === 'rangkap')
                                                <span
                                                    class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                    Rangkap
                                                </span>
                                            @else
                                                <span
                                                    class="text-[10px] font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                                                    Reguler
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Pemohon --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 border border-slate-200">
                                                {{ strtoupper(substr($item->pemohon?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">
                                                    {{ $item->pemohon?->name ?? '—' }}
                                                </p>
                                                <p class="text-[11px] text-slate-400 truncate">
                                                    {{ $item->pemohon?->jabatan_label ?? 'Staff' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kantor --}}
                                    <td class="px-4 py-4 text-xs font-medium text-slate-700">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span class="truncate">{{ $item->kantor?->nama ?? '—' }}</span>
                                        </div>
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                                            {{ $item->jenis_permohonan->label() }}
                                        </span>
                                    </td>

                                    {{-- Waktu Eksekusi --}}
                                    <td class="px-4 py-4 text-xs text-slate-600">
                                        <div class="flex items-center gap-1.5 font-medium">
                                            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $item->updated_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                                WIB</span>
                                        </div>
                                    </td>

                                    {{-- PDF --}}
                                    <td class="px-4 py-4">
                                        @if ($item->pdf_path)
                                            <a href="{{ route('permohonan.pdf', $item) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Unduh PDF
                                            </a>
                                        @else
                                            <span
                                                class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                                Generating...
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('eksekusi.show', $item) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($riwayat->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $riwayat->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- ── 4. Bottom Link ── --}}
        <div class="pt-2 pb-6 flex items-center justify-between">
            <a href="{{ route('eksekusi.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Antrean Eksekusi
            </a>
            <span class="text-xs text-slate-400">eFRUID IT Operations &copy; BPR Artha Pamenang</span>
        </div>

    </div>
@endsection
