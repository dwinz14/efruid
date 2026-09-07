@extends('layouts.app')
@section('title', 'Login History')
@section('page-title', 'Login History')

@section('content')
    <div class="space-y-4">

        {{-- Summary --}}
        <div class="grid grid-cols-3 gap-3">
            @php
                $loginSuccess = $summary[\App\Enums\AksiAudit::USER_LOGIN->value] ?? 0;
                $loginFailed = $summary[\App\Enums\AksiAudit::USER_LOGIN_FAILED->value] ?? 0;
                $loginLogout = $summary[\App\Enums\AksiAudit::USER_LOGOUT->value] ?? 0;
            @endphp
            <div class="card card-body text-center py-3">
                <p class="text-2xl font-bold text-green-700">{{ number_format($loginSuccess) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Login Berhasil</p>
            </div>
            <div class="card card-body text-center py-3">
                <p class="text-2xl font-bold {{ $loginFailed > 0 ? 'text-red-700' : 'text-slate-800' }}">
                    {{ number_format($loginFailed) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Login Gagal</p>
            </div>
            <div class="card card-body text-center py-3">
                <p class="text-2xl font-bold text-slate-700">{{ number_format($loginLogout) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Logout</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card card-body">
            <form method="GET" action="{{ route('admin.security.login-history') }}"
                class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="label">User</label>
                    <select name="user_id" class="input w-48">
                        <option value="">Semua User</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Tipe Event</label>
                    <select name="aksi" class="input w-44">
                        <option value="">Semua</option>
                        @foreach ($aksiOptions as $a)
                            <option value="{{ $a->value }}" @selected(request('aksi') === $a->value)>{{ $a->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">IP Address</label>
                    <input name="ip" type="text" value="{{ request('ip') }}" class="input w-36"
                        placeholder="192.168.x.x">
                </div>
                <div>
                    <label class="label">Dari</label>
                    <input name="dari" type="date" value="{{ request('dari', now()->subDays(7)->format('Y-m-d')) }}"
                        class="input w-36">
                </div>
                <div>
                    <label class="label">Sampai</label>
                    <input name="sampai" type="date" value="{{ request('sampai', now()->format('Y-m-d')) }}"
                        class="input w-36">
                </div>
                <button type="submit" class="btn-secondary">Filter</button>
                <a href="{{ route('admin.security.login-history') }}" class="btn-ghost">Reset</a>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header">
                <p class="text-sm font-semibold text-slate-800">{{ number_format($logs->total()) }} record</p>
            </div>
            @if ($logs->isEmpty())
                <div class="card-body text-center py-12">
                    <p class="text-slate-500">Tidak ada data ditemukan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>IP Address</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                @php
                                    $isFailed = $log->aksi->value === \App\Enums\AksiAudit::USER_LOGIN_FAILED->value;
                                @endphp
                                <tr class="{{ $isFailed ? 'bg-red-50/50' : '' }}">
                                    <td class="text-xs text-slate-500 whitespace-nowrap">
                                        {{ $log->created_at->locale('id')->isoFormat('D MMM Y, HH:mm:ss') }}
                                    </td>
                                    <td>
                                        @if ($log->user)
                                            <a href="{{ route('admin.users.show', $log->user) }}"
                                                class="text-sm font-medium text-brand-600 hover:underline">
                                                {{ $log->user->name }}
                                            </a>
                                        @else
                                            <span class="text-sm text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($isFailed)
                                            <span class="badge badge-cancelled text-xs">{{ $log->aksi->label() }}</span>
                                        @elseif($log->aksi->value === \App\Enums\AksiAudit::USER_LOGIN->value)
                                            <span class="badge badge-approved text-xs">{{ $log->aksi->label() }}</span>
                                        @else
                                            <span class="badge badge-draft text-xs">{{ $log->aksi->label() }}</span>
                                        @endif
                                    </td>
                                    <td class="font-mono text-xs text-slate-500">{{ $log->ip_address ?? '—' }}</td>
                                    <td class="text-xs text-slate-500">
                                        @if ($isFailed && isset($log->after['reason']))
                                            {{ $log->after['reason'] === 'wrong_password' ? 'Password salah' : $log->after['reason'] }}
                                            @if (isset($log->after['failed_login_count']))
                                                · percobaan ke-{{ $log->after['failed_login_count'] }}
                                            @endif
                                        @elseif($isFailed && isset($log->after['retry_after_seconds']))
                                            Rate limited · coba lagi {{ $log->after['retry_after_seconds'] }}s
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($logs->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">{{ $logs->links() }}</div>
                @endif
            @endif
        </div>

    </div>
@endsection
