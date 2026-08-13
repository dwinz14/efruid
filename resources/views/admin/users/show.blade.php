@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Info utama --}}
    <div class="card card-body">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-brand-600 rounded-full flex items-center
                            justify-center flex-shrink-0">
                    <span class="text-white text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $user->nik }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if($user->is_active)
                    <span class="badge badge-approved">Aktif</span>
                @else
                    <span class="badge badge-cancelled">Nonaktif</span>
                @endif
                @if($user->email_verified)
                    <span class="badge badge-approved">Email Verified</span>
                @else
                    <span class="badge badge-pending">Belum Verifikasi</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5 pt-5
                    border-t border-surface-border text-sm">
            <div>
                <p class="text-slate-400 text-xs">Kantor</p>
                <p class="font-medium text-slate-800 mt-0.5">
                    {{ $user->kantor?->label ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Jabatan</p>
                <p class="font-medium text-slate-800 mt-0.5">{{ $user->jabatan_label }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Login Terakhir</p>
                <p class="font-medium text-slate-800 mt-0.5">
                    {{ $user->last_login_at
                        ? $user->last_login_at->locale('id')->isoFormat('D MMM Y, HH:mm')
                        : 'Belum pernah' }}
                </p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">IP Terakhir</p>
                <p class="font-mono text-slate-800 mt-0.5 text-xs">
                    {{ $user->last_login_ip ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Tanda Tangan</p>
                <p class="font-medium text-slate-800 mt-0.5">
                    {{ $user->signature_path ? 'Ada' : 'Belum upload' }}
                </p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Bergabung</p>
                <p class="font-medium text-slate-800 mt-0.5">
                    {{ $user->created_at->locale('id')->isoFormat('D MMM Y') }}
                </p>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($user->roles as $role)
                <span class="badge badge-pending">{{ $role->label }}</span>
            @endforeach
        </div>

        <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary btn-sm">
                Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost btn-sm">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Reset password --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-semibold text-slate-800">Reset Password</h3>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.users.resetPassword', $user) }}"
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label label-required">Password Baru</label>
                        <input name="password" type="password"
                               class="input @error('password') input-error @enderror"
                               placeholder="Min. 8 karakter" required>
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label label-required">Konfirmasi Password</label>
                        <input name="password_confirmation" type="password"
                               class="input" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn-danger btn-sm" :disabled="loading">
                        <span x-text="loading ? 'Mereset...' : 'Reset Password'">
                            Reset Password
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Permohonan terbaru --}}
    @if($recentPermohonan->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">
                    Permohonan Terbaru
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto-style">
                    <thead>
                        <tr>
                            <th>Nomor Dokumen</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPermohonan as $p)
                            <tr>
                                <td class="font-mono text-xs">
                                    {{ $p->nomor_dokumen ?? '— Draft —' }}
                                </td>
                                <td>{{ $p->jenis_permohonan->label() }}</td>
                                <td>
                                    <span class="{{ $p->status->badgeClass() }}">
                                        {{ $p->status->label() }}
                                    </span>
                                </td>
                                <td class="text-sm text-slate-500">
                                    {{ $p->created_at->locale('id')->isoFormat('D MMM Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
