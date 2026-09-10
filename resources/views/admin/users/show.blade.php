@extends('layouts.app')
@section('title', 'Detail User — ' . $user->name)
@section('page-title', 'User 360°')

@section('content')
    <div class="space-y-4" x-data="{ tab: 'profile' }">
        {{-- Header --}}
        <div class="card card-body">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-brand-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ $user->name }}</h2>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $user->nik }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @php $status = $user->accountStatus(); @endphp
                    @if ($status === 'ACTIVE')
                        <span class="badge badge-approved">Aktif</span>
                    @elseif($status === 'SUSPENDED')
                        <span class="badge badge-cancelled">Suspended</span>
                    @elseif($status === 'LOCKED')
                        <span class="badge badge-cancelled"
                            style="background:oklch(0.931 0.077 55.77 / .3);color:oklch(0.553 0.195 38.402);">Locked</span>
                    @elseif($status === 'PENDING_VERIFICATION')
                        <span class="badge badge-pending">Pending OTP</span>
                    @else
                        <span class="badge badge-cancelled">Nonaktif</span>
                    @endif
                    @if ($user->email_verified)
                        <span class="badge badge-approved">Email Verified</span>
                    @else
                        <span class="badge badge-pending">Belum Verifikasi</span>
                    @endif
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary btn-sm">Edit</a>
                    <a href="{{ route('admin.users.index') }}" class="btn-ghost btn-sm">← Kembali</a>
                </div>
            </div>

            {{-- Tab Nav --}}
            <div class="flex gap-1 mt-5 pt-4 border-t border-surface-border overflow-x-auto">
                @foreach (['profile' => 'Profile', 'sessions' => 'Sessions', 'activity' => 'Activity', 'permohonan' => 'Permohonan', 'security' => 'Security'] as $key => $label)
                    <button @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'bg-brand-50 text-brand-700 font-semibold' :
                            'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm rounded-lg whitespace-nowrap transition-colors">
                        {{ $label }}
                        @if ($key === 'sessions' && $sessions->count() > 0)
                            <span class="ml-1 text-xs font-bold text-brand-600">({{ $sessions->count() }})</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ── TAB: PROFILE ── --}}
        <div x-show="tab === 'profile'" class="space-y-4">
            <div class="card card-body">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 text-sm">
                    <div>
                        <p class="text-slate-400 text-xs">Kantor</p>
                        <p class="font-medium text-slate-800 mt-0.5">{{ $user->kantor?->label ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Jabatan</p>
                        <p class="font-medium text-slate-800 mt-0.5">{{ $user->jabatan_label }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Role</p>
                        <div class="flex flex-wrap gap-1 mt-0.5">
                            @foreach ($user->roles as $role)
                                <span class="badge badge-pending text-xs">{{ $role->label }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Login Terakhir</p>
                        <p class="font-medium text-slate-800 mt-0.5">
                            {{ $user->last_login_at ? $user->last_login_at->locale('id')->isoFormat('D MMM Y, HH:mm') : 'Belum pernah' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">IP Terakhir</p>
                        <p class="font-mono text-slate-800 mt-0.5 text-xs">{{ $user->last_login_ip ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Bergabung</p>
                        <p class="font-medium text-slate-800 mt-0.5">
                            {{ $user->created_at->locale('id')->isoFormat('D MMM Y') }}</p>
                    </div>
                    @if ($user->isSuspended())
                        <div class="col-span-2">
                            <p class="text-slate-400 text-xs">Alasan Suspend</p>
                            <p class="text-red-600 mt-0.5 text-sm">{{ $user->suspension_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card card-body">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Aksi Admin</h3>
                <div class="flex flex-wrap gap-3">

                    {{-- Manual Verify --}}
                    @if (!$user->email_verified && $user->is_active)
                        <div x-data="{ open: false }">
                            <button @click="open = true" class="btn-primary btn-sm">Verifikasi Manual</button>
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                style="display:none">
                                <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                    <h3 class="font-semibold text-slate-900 mb-3">Verifikasi Manual — {{ $user->name }}
                                    </h3>
                                    <form method="POST" action="{{ route('admin.users.manualVerify', $user) }}">
                                        @csrf
                                        <div class="mb-4"><label class="label label-required">Alasan</label>
                                            <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                placeholder="Alasan verifikasi manual..."></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2"><button type="button" @click="open = false"
                                                class="btn-ghost">Batal</button><button type="submit"
                                                class="btn-primary">Verifikasi</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Suspend / Unsuspend --}}
                    @if (!$user->isSuperAdmin())
                        @if ($user->isSuspended())
                            <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}"
                                onsubmit="return confirm('Cabut suspend akun ini?')">
                                @csrf <button class="btn-secondary btn-sm">Cabut Suspend</button>
                            </form>
                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = true" class="btn-danger btn-sm">Suspend Akun</button>
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                    style="display:none">
                                    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                        <h3 class="font-semibold text-red-700 mb-3">Suspend Akun — {{ $user->name }}</h3>
                                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                            @csrf
                                            <div class="mb-4"><label class="label label-required">Alasan Suspend</label>
                                                <textarea name="reason" rows="3" required class="input w-full resize-none" placeholder="Alasan suspend..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2"><button type="button"
                                                    @click="open = false" class="btn-ghost">Batal</button><button
                                                    type="submit" class="btn-danger">Suspend</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Lock / Unlock --}}
                        @if ($user->isLocked())
                            <form method="POST" action="{{ route('admin.users.unlock', $user) }}"
                                onsubmit="return confirm('Buka kunci akun ini?')">
                                @csrf <button class="btn-secondary btn-sm">Buka Kunci</button>
                            </form>
                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = true" class="btn-secondary btn-sm">Kunci Akun</button>
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                    style="display:none">
                                    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                        <h3 class="font-semibold text-slate-900 mb-3">Kunci Akun — {{ $user->name }}
                                        </h3>
                                        <form method="POST" action="{{ route('admin.users.lock', $user) }}">
                                            @csrf
                                            <div class="mb-4"><label class="label label-required">Alasan</label>
                                                <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                    placeholder="Alasan kunci akun..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2"><button type="button"
                                                    @click="open = false" class="btn-ghost">Batal</button><button
                                                    type="submit" class="btn-danger">Kunci</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            </div>

            {{-- Reset Password --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Reset Password</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}"
                        x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="label label-required">Password Baru</label>
                                <input name="password" type="password"
                                    class="input @error('password') input-error @enderror" placeholder="Min. 8 karakter"
                                    required>
                                @error('password')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div><label class="label label-required">Konfirmasi Password</label>
                                <input name="password_confirmation" type="password" class="input"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-danger btn-sm" :disabled="loading">
                                <span x-text="loading ? 'Mereset...' : 'Reset Password'">Reset Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── TAB: SESSIONS ── --}}
        <div x-show="tab === 'sessions'" class="space-y-4">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-800">Sessions Aktif — {{ $sessions->count() }} session
                    </h3>
                    @if ($sessions->count() > 0)
                        <div x-data="{ open: false }">
                            <button @click="open = true" class="btn-danger btn-sm">Logout Semua</button>
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                style="display:none">
                                <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                    <h3 class="font-semibold text-red-700 mb-3">Logout Semua Session</h3>
                                    <p class="text-sm text-slate-600 mb-4">Semua session
                                        <strong>{{ $user->name }}</strong> ({{ $sessions->count() }} session) akan
                                        diterminasi.
                                    </p>
                                    <form method="POST" action="{{ route('admin.users.forceLogoutAll', $user) }}">
                                        @csrf
                                        <div class="mb-4"><label class="label label-required">Alasan</label>
                                            <textarea name="reason" rows="2" required class="input w-full resize-none"
                                                placeholder="Alasan force logout..."></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2"><button type="button" @click="open = false"
                                                class="btn-ghost">Batal</button><button type="submit"
                                                class="btn-danger">Logout Semua</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if ($sessions->isEmpty())
                    <div class="card-body text-center py-10">
                        <p class="text-sm text-slate-500">Tidak ada session aktif.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-auto-style">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Last Activity</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sessions as $sess)
                                    <tr>
                                        <td>
                                            @if ($sess->is_online)
                                                <span class="badge badge-approved">Online</span>
                                            @elseif($sess->is_idle)
                                                <span class="badge badge-pending">Idle</span>
                                            @else
                                                <span class="badge badge-cancelled">Offline</span>
                                            @endif
                                        </td>
                                        <td class="font-mono text-xs">{{ $sess->ip_address ?? '—' }}</td>
                                        <td class="text-xs text-slate-500 max-w-xs truncate">
                                            {{ $sess->user_agent ? Str::limit($sess->user_agent, 60) : '—' }}</td>
                                        <td class="text-xs text-slate-500">
                                            {{ $sess->last_active->locale('id')->diffForHumans() }}</td>
                                        <td class="text-right">
                                            <div x-data="{ open: false }">
                                                <button @click="open = true" class="btn-ghost btn-sm text-red-600">Force
                                                    Logout</button>
                                                <div x-show="open"
                                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                    style="display:none">
                                                    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                                    <div
                                                        class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                                        <h3 class="font-semibold text-slate-900 mb-1">Force Logout Session
                                                        </h3>
                                                        <p class="text-sm text-slate-500 mb-1">User:
                                                            <strong>{{ $user->name }}</strong>
                                                        </p>
                                                        <p class="text-xs font-mono text-slate-400 mb-4">IP:
                                                            {{ $sess->ip_address }}</p>
                                                        <form method="POST"
                                                            action="{{ route('admin.users.forceLogout', $user) }}">
                                                            @csrf
                                                            <input type="hidden" name="session_id"
                                                                value="{{ $sess->id }}">
                                                            <div class="mb-4"><label
                                                                    class="label label-required">Alasan</label>
                                                                <textarea name="reason" rows="2" required class="input w-full resize-none"
                                                                    placeholder="Alasan force logout..."></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-2"><button type="button"
                                                                    @click="open = false"
                                                                    class="btn-ghost">Batal</button><button type="submit"
                                                                    class="btn-danger btn-sm">Force Logout</button></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── TAB: ACTIVITY ── --}}
        <div x-show="tab === 'activity'" class="space-y-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Activity Timeline (30 terbaru)</h3>
                </div>
                @if ($activity->isEmpty())
                    <div class="card-body text-center py-10">
                        <p class="text-sm text-slate-500">Belum ada aktivitas.</p>
                    </div>
                @else
                    <div class="divide-y divide-surface-border">
                        @foreach ($activity as $log)
                            <div class="px-4 py-3 flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-brand-400 mt-1.5 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800">{{ $log->aksi->label() }}</p>
                                    @if ($log->nomor_dokumen)
                                        <p class="text-xs font-mono text-brand-600">{{ $log->nomor_dokumen }}</p>
                                    @endif
                                    @if ($log->ip_address)
                                        <p class="text-xs text-slate-400 font-mono">{{ $log->ip_address }}</p>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 whitespace-nowrap flex-shrink-0">
                                    {{ $log->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-body border-t border-surface-border">
                        <a href="{{ route('admin.audit-logs.index', ['user_id' => $user->id]) }}"
                            class="text-sm text-brand-600 hover:underline">
                            Lihat semua di Audit Log →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── TAB: PERMOHONAN ── --}}
        <div x-show="tab === 'permohonan'" class="space-y-4">
            {{-- Summary --}}
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                @php
                    use App\Enums\StatusPermohonan;
                    $summaryItems = [
                        ['label' => 'Total', 'status' => null],
                        ['label' => 'Draft', 'status' => StatusPermohonan::DRAFT->value],
                        ['label' => 'Pending', 'status' => StatusPermohonan::PENDING_ATASAN->value],
                        ['label' => 'Approved', 'status' => StatusPermohonan::PENDING_IT->value],
                        ['label' => 'Ditolak', 'status' => StatusPermohonan::REJECTED->value],
                        ['label' => 'Executed', 'status' => StatusPermohonan::EXECUTED->value],
                    ];
                @endphp
                @foreach ($summaryItems as $item)
                    <div class="card card-body text-center py-3">
                        <p class="text-xl font-bold text-slate-900">
                            {{ $item['status'] ? $permohonanSummary[$item['status']] ?? 0 : $permohonanSummary->sum() }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Semua Permohonan</h3>
                </div>
                @if ($permohonan->isEmpty())
                    <div class="card-body text-center py-10">
                        <p class="text-sm text-slate-500">Belum ada permohonan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-auto-style">
                            <thead>
                                <tr>
                                    <th>Nomor Dokumen</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permohonan as $p)
                                    <tr>
                                        <td class="font-mono text-xs">{{ $p->nomor_dokumen ?? '— Draft —' }}</td>
                                        <td class="text-sm">{{ $p->jenis_permohonan->label() }}</td>
                                        <td><span class="{{ $p->status->badgeClass() }}">{{ $p->status->label() }}</span>
                                        </td>
                                        <td class="text-xs text-slate-500">
                                            {{ $p->created_at->locale('id')->isoFormat('D MMM Y') }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.permohonan.show', $p) }}"
                                                class="btn-ghost btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($permohonan->hasPages())
                        <div class="px-4 py-3 border-t border-surface-border">{{ $permohonan->links() }}</div>
                    @endif
                @endif
            </div>
        </div>

        {{-- ── TAB: SECURITY ── --}}
        <div x-show="tab === 'security'" class="space-y-4">
            <div class="card card-body">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Status Keamanan Akun</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-slate-400 text-xs">Status Akun</p>
                        <p
                            class="font-semibold mt-0.5 {{ $user->accountStatus() === 'ACTIVE' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->accountStatus() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Gagal Login</p>
                        <p
                            class="font-semibold mt-0.5 {{ $user->failed_login_count > 0 ? 'text-amber-600' : 'text-slate-800' }}">
                            {{ $user->failed_login_count }}x
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Dikunci Sejak</p>
                        <p class="font-medium text-slate-800 mt-0.5">
                            {{ $user->locked_at ? $user->locked_at->locale('id')->isoFormat('D MMM Y, HH:mm') : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs">Disuspend Sejak</p>
                        <p class="font-medium text-slate-800 mt-0.5">
                            {{ $user->suspended_at ? $user->suspended_at->locale('id')->isoFormat('D MMM Y, HH:mm') : '—' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Security Log</h3>
                </div>
                @if ($securityLog->isEmpty())
                    <div class="card-body text-center py-10">
                        <p class="text-sm text-slate-500">Tidak ada event keamanan.</p>
                    </div>
                @else
                    <div class="divide-y divide-surface-border">
                        @foreach ($securityLog as $log)
                            <div class="px-4 py-3 flex items-start gap-3">
                                <div
                                    class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0
                            {{ str_contains($log->aksi->value, 'failed') || str_contains($log->aksi->value, 'locked') || str_contains($log->aksi->value, 'suspended') ? 'bg-red-400' : 'bg-green-400' }}">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-800">{{ $log->aksi->label() }}</p>
                                    @if ($log->after && isset($log->after['reason']))
                                        <p class="text-xs text-slate-500">Alasan: {{ $log->after['reason'] }}</p>
                                    @endif
                                    <p class="text-xs text-slate-400 font-mono">{{ $log->ip_address }}</p>
                                </div>
                                <p class="text-xs text-slate-400 whitespace-nowrap">
                                    {{ $log->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
