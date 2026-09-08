@extends('layouts.app')

@section('title', 'Approval Atasan')
@section('page-title', 'Approval Atasan')

@section('content')
    <div class="space-y-6">

        {{-- ── Top Header & Page Actions ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-100 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Daftar Permohonan Pending</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Permohonan yang memerlukan peninjauan dan persetujuan Anda sebagai Atasan
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
                {{-- Quick Link ke Riwayat --}}
                <a href="{{ route('approval.atasan.riwayat') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200/80 shadow-sm hover:bg-slate-50 hover:text-brand-600 hover:border-brand-200 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95">
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
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        @endif
                        <span
                            class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $pendingCount > 0 ? 'bg-amber-500' : 'bg-slate-300' }}"></span>
                    </span>
                    <span class="text-xs font-semibold text-slate-500">Menunggu:</span>
                    <span class="text-sm font-bold {{ $pendingCount > 0 ? 'text-amber-600' : 'text-slate-700' }}">
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
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Menunggu Tindakan Anda</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih salah satu permohonan untuk meninjau dan memproses
                        persetujuan</p>
                </div>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ $pendingCount }} Antrean
                </span>
            </div>

            @php
                $detailRoute = fn($item) => route('approval.atasan.show', $item);
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
