@extends('layouts.app')

@section('title', 'Approval Direktur')
@section('page-title', 'Approval Direktur')

@section('content')
    <div class="space-y-6">

        {{-- ── Top Header & Page Actions ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-purple-50 rounded-xl border border-purple-100 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Daftar Persetujuan Direktur
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Permohonan hak akses yang memerlukan peninjauan dan otorisasi Direktur Utama
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
                {{-- Quick Link ke Riwayat Approval Direktur --}}
                <a href="{{ route('approval.dirut.riwayat') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 shadow-sm hover:bg-slate-50 hover:text-purple-600 hover:border-purple-200 transition-all focus:outline-none focus:ring-2 focus:ring-purple-500/20 active:scale-95">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat Approval</span>
                </a>

                {{-- Badge Counter --}}
                <div class="flex items-center gap-2.5 bg-white px-4 py-2 rounded-xl border border-slate-200/80 shadow-sm">
                    <span class="relative flex h-2.5 w-2.5">
                        @if ($pendingCount > 0)
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                        @endif
                        <span
                            class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $pendingCount > 0 ? 'bg-purple-600' : 'bg-slate-300' }}"></span>
                    </span>
                    <span class="text-xs font-semibold text-slate-500">Menunggu:</span>
                    <span class="text-sm font-bold {{ $pendingCount > 0 ? 'text-purple-600' : 'text-slate-700' }}">
                        {{ $pendingCount }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Container Tabel Permohonan ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.1s;">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Menunggu Otorisasi Direktur</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih salah satu permohonan untuk meninjau lembar FRUID dan
                        memberikan keputusan</p>
                </div>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ $pendingCount }} Antrean
                </span>
            </div>

            @php
                $detailRoute = fn($item) => route('approval.dirut.show', $item);
            @endphp

            {{-- Area Tabel / Empty State (via Partial) --}}
            @include('approval.partials.permohonan-table')

            {{-- Navigasi Paginasi --}}
            @if ($pending->hasPages())
                <div class="px-5 sm:px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $pending->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
