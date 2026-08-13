@extends('layouts.app')

@section('title', 'Detail Audit Log')
@section('page-title', 'Detail Audit Log')

@section('content')
    <div class="max-w-2xl mx-auto space-y-4">

        <div class="card card-body">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-400 text-xs">ID</p>
                    <p class="font-mono font-medium">{{ $auditLog->id }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs">Waktu</p>
                    <p class="font-medium">
                        {{ $auditLog->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm:ss') }}
                    </p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs">User</p>
                    <p class="font-medium">{{ $auditLog->user?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs">Aksi</p>
                    <span class="badge badge-draft">
                        {{ $auditLog->aksi instanceof \App\Enums\AksiAudit ? $auditLog->aksi->label() : $auditLog->aksi }}
                    </span>
                </div>
                <div>
                    <p class="text-slate-400 text-xs">Nomor Dokumen</p>
                    <p class="font-mono font-medium">{{ $auditLog->nomor_dokumen ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs">IP Address</p>
                    <p class="font-mono">{{ $auditLog->ip_address ?? '—' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-slate-400 text-xs">User Agent</p>
                    <p class="text-xs text-slate-500 break-all">
                        {{ $auditLog->user_agent ?? '—' }}
                    </p>
                </div>
            </div>
        </div>

        @if ($auditLog->before)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Before</h3>
                </div>
                <div class="card-body">
                    <pre class="text-xs text-slate-600 bg-slate-50 rounded p-3
                            overflow-x-auto">{{ json_encode($auditLog->before, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        @if ($auditLog->after)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">After</h3>
                </div>
                <div class="card-body">
                    <pre class="text-xs text-slate-600 bg-slate-50 rounded p-3
                            overflow-x-auto">{{ json_encode($auditLog->after, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        <div class="pb-4">
            <a href="{{ route('admin.audit-logs.index') }}" class="btn-ghost btn-sm">
                ← Kembali
            </a>
        </div>

    </div>
@endsection
