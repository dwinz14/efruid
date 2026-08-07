@extends('layouts.app')

@section('title', 'Approval Atasan')
@section('page-title', 'Approval Atasan')

@section('content')
<div class="space-y-4">

    {{-- Summary --}}
    <div class="card card-body">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7
                             a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2
                             M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $pendingCount }}</p>
                <p class="text-sm text-slate-500">Permohonan menunggu persetujuan Anda</p>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-sm font-semibold text-slate-800">Daftar Permohonan Pending</h2>
        </div>
        @php
            $detailRoute = fn($item) => route('approval.atasan.show', $item);
        @endphp
        @include('approval.partials.permohonan-table')
        @if($pending->hasPages())
            <div class="px-4 py-3 border-t border-surface-border">
                {{ $pending->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
