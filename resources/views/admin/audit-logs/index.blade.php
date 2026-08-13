@extends('layouts.app')

@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')
    <div class="space-y-4">

        {{-- Filter + Export --}}
        <div class="card card-body">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="label">User</label>
                    <select name="user_id" class="input w-48">
                        <option value="">Semua User</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Aksi</label>
                    <select name="aksi" class="input w-48">
                        <option value="">Semua Aksi</option>
                        @foreach ($aksis as $a)
                            <option value="{{ $a->value }}" @selected(request('aksi') === $a->value)>
                                {{ $a->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Nomor Dokumen</label>
                    <input name="nomor_dokumen" type="text" value="{{ request('nomor_dokumen') }}" class="input w-44"
                        placeholder="FRUID/...">
                </div>
                <div>
                    <label class="label">Dari</label>
                    <input name="dari" type="date" value="{{ request('dari') }}" class="input w-36">
                </div>
                <div>
                    <label class="label">Sampai</label>
                    <input name="sampai" type="date" value="{{ request('sampai') }}" class="input w-36">
                </div>
                <button type="submit" class="btn-secondary">Terapkan</button>
                @if (request()->hasAny(['user_id', 'aksi', 'nomor_dokumen', 'dari', 'sampai']))
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn-ghost">Reset</a>
                @endif
            </form>

            {{-- Export buttons --}}
            <div class="flex gap-2 mt-3 pt-3 border-t border-surface-border">
                <span class="text-xs text-slate-500 self-center">Export sesuai filter:</span>
                <a href="{{ route('admin.audit-logs.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}"
                    class="btn-secondary btn-sm">
                    Export Excel
                </a>
                <a href="{{ route('admin.audit-logs.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                    class="btn-secondary btn-sm">
                    Export CSV
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    {{ number_format($logs->total()) }} record
                </h2>
            </div>
            @if ($logs->isEmpty())
                <div class="card-body text-center py-12">
                    <p class="text-slate-500">Tidak ada log ditemukan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Nomor Dokumen</th>
                                <th>IP</th>
                                <th class="text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td class="text-xs text-slate-500 whitespace-nowrap">
                                        {{ $log->created_at->locale('id')->isoFormat('D MMM Y, HH:mm:ss') }}
                                    </td>
                                    <td class="text-sm font-medium">
                                        {{ $log->user?->name ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-draft text-xs">
                                            {{ $log->aksi instanceof \App\Enums\AksiAudit ? $log->aksi->label() : $log->aksi }}
                                        </span>
                                    </td>
                                    <td class="font-mono text-xs text-slate-600">
                                        {{ $log->nomor_dokumen ?? '—' }}
                                    </td>
                                    <td class="font-mono text-xs text-slate-400">
                                        {{ $log->ip_address ?? '—' }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.audit-logs.show', $log) }}"
                                            class="btn-ghost btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($logs->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">
                        {{ $logs->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
