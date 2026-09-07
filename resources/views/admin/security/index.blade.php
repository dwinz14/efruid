@extends('layouts.app')
@section('title', 'Security Center')
@section('page-title', 'Security Center')

@section('content')
    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-2">
            <p class="text-sm text-slate-500">Pantau keamanan sistem secara real-time.</p>
            <div class="flex gap-2">
                <a href="{{ route('admin.security.login-history') }}" class="btn-secondary btn-sm">Login History</a>
                <a href="{{ route('admin.sessions.index') }}" class="btn-secondary btn-sm">Session Monitor</a>
            </div>
        </div>

        {{-- KPI Grid --}}
        @php
            $kpiCards = [
                [
                    'label' => 'Online Sekarang',
                    'value' => $kpi['online_now'],
                    'sub' => 'dalam 5 menit terakhir',
                    'color' => 'green',
                    'link' => route('admin.sessions.index'),
                ],
                [
                    'label' => 'Idle',
                    'value' => $kpi['idle_now'],
                    'sub' => '5–30 menit tidak aktif',
                    'color' => 'amber',
                    'link' => route('admin.sessions.index'),
                ],
                [
                    'label' => 'Total Sessions',
                    'value' => $kpi['total_sessions'],
                    'sub' => 'session tersimpan',
                    'color' => 'brand',
                    'link' => route('admin.sessions.index'),
                ],
                [
                    'label' => 'Akun Terkunci',
                    'value' => $kpi['locked_accounts'],
                    'sub' => 'perlu perhatian',
                    'color' => $kpi['locked_accounts'] > 0 ? 'red' : 'slate',
                    'link' => route('admin.users.index', ['status' => 'locked']),
                ],
                [
                    'label' => 'Akun Suspended',
                    'value' => $kpi['suspended_accounts'],
                    'sub' => 'akses diblokir',
                    'color' => $kpi['suspended_accounts'] > 0 ? 'red' : 'slate',
                    'link' => route('admin.users.index', ['status' => 'suspended']),
                ],
                [
                    'label' => 'Pending Verifikasi',
                    'value' => $kpi['pending_verify'],
                    'sub' => 'belum verifikasi email',
                    'color' => $kpi['pending_verify'] > 0 ? 'amber' : 'slate',
                    'link' => route('admin.users.pending'),
                ],
                [
                    'label' => 'Gagal Login Hari Ini',
                    'value' => $kpi['failed_today'],
                    'sub' => 'attempt gagal hari ini',
                    'color' => $kpi['failed_today'] > 0 ? 'red' : 'slate',
                    'link' => route('admin.security.login-history'),
                ],
                [
                    'label' => 'Gagal Login Minggu Ini',
                    'value' => $kpi['failed_this_week'],
                    'sub' => '7 hari terakhir',
                    'color' => $kpi['failed_this_week'] > 5 ? 'amber' : 'slate',
                    'link' => route('admin.security.login-history'),
                ],
            ];
            $colors = [
                'green' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'num' => 'text-green-700'],
                'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'num' => 'text-amber-700'],
                'red' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'num' => 'text-red-700'],
                'brand' => ['bg' => 'bg-brand-50', 'border' => 'border-brand-200', 'num' => 'text-brand-700'],
                'slate' => ['bg' => 'bg-white', 'border' => 'border-surface-border', 'num' => 'text-slate-800'],
            ];
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ($kpiCards as $card)
                @php $c = $colors[$card['color']]; @endphp
                <a href="{{ $card['link'] }}"
                    class="card card-body border {{ $c['border'] }} {{ $c['bg'] }} hover:shadow-card-hover transition-shadow">
                    <p class="text-2xl font-bold {{ $c['num'] }}">{{ $card['value'] }}</p>
                    <p class="text-xs font-medium text-slate-700 mt-0.5">{{ $card['label'] }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $card['sub'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Locked Accounts --}}
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-800">
                        Akun Terkunci
                        @if ($lockedUsers->count() > 0)
                            <span class="ml-1 text-xs font-bold text-red-600">({{ $lockedUsers->count() }})</span>
                        @endif
                    </h3>
                    <a href="{{ route('admin.users.index', ['status' => 'locked']) }}"
                        class="text-xs text-brand-600 font-medium">Lihat semua</a>
                </div>
                @if ($lockedUsers->isEmpty())
                    <div class="card-body text-center py-8">
                        <p class="text-sm text-slate-400">Tidak ada akun terkunci.</p>
                    </div>
                @else
                    <div class="divide-y divide-surface-border">
                        @foreach ($lockedUsers as $u)
                            <div class="px-4 py-3 flex items-center gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $u->kantor?->nama }} ·
                                        Dikunci {{ $u->locked_at->locale('id')->diffForHumans() }}
                                    </p>
                                    @if ($u->failed_login_count > 0)
                                        <p class="text-xs text-amber-600">{{ $u->failed_login_count }}x gagal login</p>
                                    @endif
                                </div>
                                <div class="flex gap-1 flex-shrink-0">
                                    <a href="{{ route('admin.users.show', $u) }}" class="btn-ghost btn-sm">Detail</a>
                                    <form method="POST" action="{{ route('admin.users.unlock', $u) }}"
                                        onsubmit="return confirm('Buka kunci akun {{ $u->name }}?')">
                                        @csrf
                                        <button class="btn-secondary btn-sm">Unlock</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Suspended Accounts --}}
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-800">
                        Akun Suspended
                        @if ($suspendedUsers->count() > 0)
                            <span class="ml-1 text-xs font-bold text-red-600">({{ $suspendedUsers->count() }})</span>
                        @endif
                    </h3>
                    <a href="{{ route('admin.users.index', ['status' => 'suspended']) }}"
                        class="text-xs text-brand-600 font-medium">Lihat semua</a>
                </div>
                @if ($suspendedUsers->isEmpty())
                    <div class="card-body text-center py-8">
                        <p class="text-sm text-slate-400">Tidak ada akun suspended.</p>
                    </div>
                @else
                    <div class="divide-y divide-surface-border">
                        @foreach ($suspendedUsers as $u)
                            <div class="px-4 py-3 flex items-center gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $u->kantor?->nama }} ·
                                        Sejak {{ $u->suspended_at->locale('id')->diffForHumans() }}
                                    </p>
                                    @if ($u->suspension_reason)
                                        <p class="text-xs text-slate-500 truncate">{{ $u->suspension_reason }}</p>
                                    @endif
                                </div>
                                <div class="flex gap-1 flex-shrink-0">
                                    <a href="{{ route('admin.users.show', $u) }}" class="btn-ghost btn-sm">Detail</a>
                                    <form method="POST" action="{{ route('admin.users.unsuspend', $u) }}"
                                        onsubmit="return confirm('Cabut suspend akun {{ $u->name }}?')">
                                        @csrf
                                        <button class="btn-secondary btn-sm">Unsuspend</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- At Risk Users (failed_login_count > 0) --}}
        @if ($atRiskUsers->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">User Berisiko — Gagal Login Berulang</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Kantor</th>
                                <th>Gagal Login</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atRiskUsers as $u)
                                <tr>
                                    <td>
                                        <div class="font-medium text-sm text-slate-800">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-400 font-mono">{{ $u->email }}</div>
                                    </td>
                                    <td class="text-sm">{{ $u->kantor?->nama ?? '—' }}</td>
                                    <td>
                                        <span
                                            class="font-bold {{ $u->failed_login_count >= 4 ? 'text-red-600' : 'text-amber-600' }}">
                                            {{ $u->failed_login_count }}x
                                        </span>
                                        @if ($u->failed_login_count >= 5)
                                            <span class="text-xs text-red-500 ml-1">threshold!</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $st = $u->accountStatus(); @endphp
                                        @if ($st === 'ACTIVE')
                                            <span class="badge badge-approved">Aktif</span>
                                        @elseif($st === 'LOCKED')
                                            <span class="badge badge-cancelled">Locked</span>
                                        @else
                                            <span class="badge badge-cancelled">{{ $st }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.users.show', $u) }}" class="btn-ghost btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Recent Security Events --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-800">Event Keamanan Terbaru</h3>
                <a href="{{ route('admin.security.login-history') }}" class="text-xs text-brand-600 font-medium">Login
                    History →</a>
            </div>
            @if ($recentEvents->isEmpty())
                <div class="card-body text-center py-8">
                    <p class="text-sm text-slate-400">Tidak ada event keamanan.</p>
                </div>
            @else
                <div class="divide-y divide-surface-border">
                    @foreach ($recentEvents as $event)
                        @php
                            $isAlert = in_array($event->aksi->value, [
                                \App\Enums\AksiAudit::USER_LOGIN_FAILED->value,
                                \App\Enums\AksiAudit::USER_ACCOUNT_LOCKED->value,
                                \App\Enums\AksiAudit::USER_SUSPENDED->value,
                            ]);
                        @endphp
                        <div class="px-4 py-3 flex items-start gap-3">
                            <div
                                class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0
                        {{ $isAlert ? 'bg-red-400' : 'bg-green-400' }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-medium text-slate-800">{{ $event->aksi->label() }}</p>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Actor: <span class="font-medium">{{ $event->user?->name ?? 'System' }}</span>
                                    @if ($event->after && isset($event->after['reason']))
                                        · Alasan: {{ Str::limit($event->after['reason'], 50) }}
                                    @endif
                                    @if ($event->after && isset($event->after['failed_login_count']))
                                        · Percobaan ke-{{ $event->after['failed_login_count'] }}
                                    @endif
                                </p>
                                @if ($event->ip_address)
                                    <p class="text-xs font-mono text-slate-400">{{ $event->ip_address }}</p>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 whitespace-nowrap flex-shrink-0">
                                {{ $event->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection
