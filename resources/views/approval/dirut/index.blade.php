@extends('layouts.app')

@section('title', 'Approval Direktur')
@section('page-title', 'Approval Direktur')

@section('content')
    <div class="space-y-4">

        <div class="card card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                                 a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591
                                 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622
                                 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $pendingCount }}</p>
                    <p class="text-sm text-slate-500">Permohonan rangkap jabatan menunggu persetujuan</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    Daftar Permohonan Rangkap Jabatan Pending
                </h2>
            </div>
            @php
                $detailRoute = fn($item) => route('approval.dirut.show', $item);
            @endphp
            @include('approval.partials.permohonan-table')
        </div>

    </div>
@endsection
