@extends('layouts.app')
@section('title', 'Pending Registrasi')
@section('page-title', 'Pending Registrasi')

@section('content')
    <div class="space-y-4">

        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">{{ $users->total() }} user menunggu verifikasi</p>
            <a href="{{ route('admin.users.index') }}" class="btn-ghost btn-sm">← Kembali</a>
        </div>

        <div class="card">
            @if ($users->isEmpty())
                <div class="card-body text-center py-12">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-500 font-medium">Tidak ada registrasi yang menunggu</p>
                    <p class="text-sm text-slate-400 mt-1">Semua user sudah terverifikasi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Kantor / Jabatan</th>
                                <th>Terdaftar</th>
                                <th>OTP Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $u)
                                <tr x-data="{ showVerify: false, showReject: false }">
                                    <td>
                                        <div class="font-medium text-slate-800 text-sm">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-400 font-mono">{{ $u->nik }} ·
                                            {{ $u->email }}</div>
                                    </td>
                                    <td class="text-sm">
                                        <div>{{ $u->kantor?->nama ?? '—' }}</div>
                                        <div class="text-xs text-slate-400">{{ $u->jabatan_label }}</div>
                                    </td>
                                    <td class="text-xs text-slate-500">
                                        {{ $u->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                        <div class="text-slate-400">{{ $u->created_at->locale('id')->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-pending">Belum Verifikasi OTP</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.users.show', $u) }}"
                                                class="btn-ghost btn-sm">Detail</a>
                                            <button @click="showVerify = true"
                                                class="btn-primary btn-sm">Verifikasi</button>
                                            <button @click="showReject = true" class="btn-danger btn-sm">Tolak</button>
                                        </div>

                                        {{-- Modal Verifikasi --}}
                                        <div x-show="showVerify"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            style="display:none">
                                            <div class="absolute inset-0 bg-black/50" @click="showVerify = false"></div>
                                            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                                <h3 class="font-semibold text-slate-900 mb-1">Verifikasi Manual</h3>
                                                <p class="text-sm text-slate-500 mb-4">
                                                    Anda akan memverifikasi akun <strong>{{ $u->name }}</strong>
                                                    secara manual tanpa OTP.
                                                </p>
                                                <form method="POST" action="{{ route('admin.users.manualVerify', $u) }}">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="label label-required">Alasan Verifikasi Manual</label>
                                                        <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                            placeholder="Contoh: OTP tidak terkirim, konfirmasi via telepon..."></textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="showVerify = false"
                                                            class="btn-ghost">Batal</button>
                                                        <button type="submit" class="btn-primary">Verifikasi
                                                            Sekarang</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Modal Tolak --}}
                                        <div x-show="showReject"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            style="display:none">
                                            <div class="absolute inset-0 bg-black/50" @click="showReject = false"></div>
                                            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                                                <h3 class="font-semibold text-red-700 mb-1">Tolak Registrasi</h3>
                                                <p class="text-sm text-slate-500 mb-4">
                                                    Akun <strong>{{ $u->name }}</strong> akan dinonaktifkan dan tidak
                                                    dapat login.
                                                </p>
                                                <form method="POST"
                                                    action="{{ route('admin.users.rejectRegistration', $u) }}">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="label label-required">Alasan Penolakan</label>
                                                        <textarea name="reason" rows="3" required class="input w-full resize-none"
                                                            placeholder="Alasan penolakan registrasi..."></textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="showReject = false"
                                                            class="btn-ghost">Batal</button>
                                                        <button type="submit" class="btn-danger">Tolak Registrasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">{{ $users->links() }}</div>
                @endif
            @endif
        </div>

    </div>
@endsection
