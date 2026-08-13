@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">Total: {{ $users->total() }} user</p>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User
        </a>
    </div>

    {{-- Filter --}}
    <div class="card card-body">
        <form method="GET" action="{{ route('admin.users.index') }}"
              class="flex gap-3 items-end flex-wrap">
            <div>
                <label class="label">Cari</label>
                <input name="search" type="text" value="{{ request('search') }}"
                       class="input w-52" placeholder="Nama, NIK, email...">
            </div>
            <div>
                <label class="label">Kantor</label>
                <select name="kantor_id" class="input w-44">
                    <option value="">Semua Kantor</option>
                    @foreach($kantor as $k)
                        <option value="{{ $k->id }}" @selected(request('kantor_id') == $k->id)>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Role</label>
                <select name="role" class="input w-40">
                    <option value="">Semua Role</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->value }}" @selected(request('role') === $r->value)>
                            {{ $r->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Status</label>
                <select name="status" class="input w-36">
                    <option value="">Semua</option>
                    <option value="aktif"    @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary">Terapkan</button>
            @if(request()->hasAny(['search','kantor_id','role','status']))
                <a href="{{ route('admin.users.index') }}" class="btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card">
        @if($users->isEmpty())
            <div class="card-body text-center py-12">
                <p class="text-slate-500">Tidak ada user ditemukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-auto-style">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Kantor</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Login Terakhir</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="font-medium text-slate-800">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono">
                                        {{ $user->nik }} &middot; {{ $user->email }}
                                    </div>
                                </td>
                                <td class="text-sm">{{ $user->kantor?->nama ?? '—' }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-pending text-xs">
                                                {{ $role->label }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge badge-approved">Aktif</span>
                                    @else
                                        <span class="badge badge-cancelled">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $user->last_login_at
                                        ? $user->last_login_at->locale('id')->diffForHumans()
                                        : '—' }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn-secondary btn-sm">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-4 py-3 border-t border-surface-border">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection

