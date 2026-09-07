@extends('layouts.app')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
    <div class="space-y-4">

        {{-- KPI Bar --}}
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
            @php
                $kpiItems = [
                    ['label' => 'Total User', 'value' => $kpi['total'], 'color' => 'slate'],
                    ['label' => 'Aktif', 'value' => $kpi['aktif'], 'color' => 'green'],
                    ['label' => 'Online', 'value' => $kpi['online'], 'color' => 'brand'],
                    ['label' => 'Pending OTP', 'value' => $kpi['pending_verifikasi'], 'color' => 'amber'],
                    ['label' => 'Suspended', 'value' => $kpi['suspended'], 'color' => 'red'],
                    ['label' => 'Locked', 'value' => $kpi['locked'], 'color' => 'orange'],
                ];
                $kpiColor = [
                    'slate' => 'text-slate-700 bg-slate-100',
                    'green' => 'text-green-700 bg-green-100',
                    'brand' => 'text-brand-700 bg-brand-100',
                    'amber' => 'text-amber-700 bg-amber-100',
                    'red' => 'text-red-700 bg-red-100',
                    'orange' => 'text-orange-700 bg-orange-100',
                ];
            @endphp
            @foreach ($kpiItems as $item)
                <div class="card card-body text-center py-3">
                    <p class="text-xl font-bold text-slate-900">{{ $item['value'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $item['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex gap-2">
                <a href="{{ route('admin.users.pending') }}" class="btn-secondary btn-sm">
                    Pending Registrasi
                    @if ($kpi['pending_verifikasi'] > 0)
                        <span
                            class="ml-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-amber-500 rounded-full">
                            {{ $kpi['pending_verifikasi'] > 9 ? '9+' : $kpi['pending_verifikasi'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.sessions.index') }}" class="btn-secondary btn-sm">
                    Session Monitor
                </a>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>

        {{-- Filter --}}
        <div class="card card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="label">Cari</label>
                    <input name="search" type="text" value="{{ request('search') }}" class="input w-52"
                        placeholder="Nama, NIK, email...">
                </div>
                <div>
                    <label class="label">Kantor</label>
                    <select name="kantor_id" class="input w-44">
                        <option value="">Semua Kantor</option>
                        @foreach ($kantor as $k)
                            <option value="{{ $k->id }}" @selected(request('kantor_id') == $k->id)>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Role</label>
                    <select name="role" class="input w-40">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->value }}" @selected(request('role') === $r->value)>{{ $r->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="input w-44">
                        <option value="">Semua Status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                        <option value="locked" @selected(request('status') === 'locked')>Locked</option>
                        <option value="pending_verifikasi" @selected(request('status') === 'pending_verifikasi')>Pending Verifikasi</option>
                    </select>
                </div>
                <div>
                    <label class="label">Online</label>
                    <select name="online" class="input w-32">
                        <option value="">Semua</option>
                        <option value="1" @selected(request('online') === '1')>Online</option>
                        <option value="0" @selected(request('online') === '0')>Offline</option>
                    </select>
                </div>
                <button type="submit" class="btn-secondary">Filter</button>
                @if (request()->hasAny(['search', 'kantor_id', 'role', 'status', 'online']))
                    <a href="{{ route('admin.users.index') }}" class="btn-ghost">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card">
            @if ($users->isEmpty())
                <div class="card-body text-center py-12">
                    <p class="text-slate-500">Tidak ada user ditemukan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Kantor / Jabatan</th>
                                <th>Role</th>
                                <th>Status Akun</th>
                                <th>Login Terakhir</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $u)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            {{-- Online dot --}}
                                            <span
                                                class="w-2 h-2 rounded-full flex-shrink-0 {{ in_array($u->id, $onlineIds) ? 'bg-green-500' : 'bg-slate-200' }}"
                                                title="{{ in_array($u->id, $onlineIds) ? 'Online' : 'Offline' }}"></span>
                                            <div>
                                                <div class="font-medium text-slate-800 text-sm">{{ $u->name }}</div>
                                                <div class="text-xs text-slate-400 font-mono">{{ $u->nik }} ·
                                                    {{ $u->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm">
                                        <div>{{ $u->kantor?->nama ?? '—' }}</div>
                                        <div class="text-xs text-slate-400">{{ $u->jabatan_label }}</div>
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($u->roles as $role)
                                                <span class="badge badge-pending text-xs">{{ $role->label }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        @php $status = $u->accountStatus(); @endphp
                                        @if ($status === 'ACTIVE')
                                            <span class="badge badge-approved">Aktif</span>
                                        @elseif($status === 'SUSPENDED')
                                            <span class="badge badge-cancelled">Suspended</span>
                                        @elseif($status === 'LOCKED')
                                            <span class="badge badge-cancelled"
                                                style="background-color: #fed7aa; color: #c2410c;">Locked</span>
                                        @elseif($status === 'PENDING_VERIFICATION')
                                            <span class="badge badge-pending">Pending OTP</span>
                                        @else
                                            <span class="badge badge-cancelled">Nonaktif</span>
                                        @endif
                                        @if (!$u->email_verified && $u->is_active)
                                            <span class="block text-xs text-amber-600 mt-0.5">Belum verifikasi</span>
                                        @endif
                                    </td>
                                    <td class="text-xs text-slate-500">
                                        {{ $u->last_login_at ? $u->last_login_at->locale('id')->diffForHumans() : '—' }}
                                        @if ($u->last_login_ip)
                                            <div class="font-mono text-slate-400">{{ $u->last_login_ip }}</div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.users.show', $u) }}"
                                            class="btn-secondary btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">
                        {{ $users->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
