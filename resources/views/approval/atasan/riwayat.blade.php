@extends('layouts.app')

@section('title', 'Riwayat Approval Saya')
@section('page-title', 'Riwayat Approval Saya')

@section('content')
    <div class="space-y-4">

        <div class="card card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $riwayat->total() }}</p>
                    <p class="text-sm text-slate-500">Total permohonan yang sudah Anda proses</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">Riwayat Persetujuan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Semua permohonan yang pernah Anda setujui atau tolak</p>
            </div>

            @if ($riwayat->isEmpty())
                <div class="card-body text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-500 font-medium">Belum ada riwayat</p>
                    <p class="text-sm text-slate-400 mt-1">Permohonan yang sudah Anda proses akan muncul di sini</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>Nomor Dokumen</th>
                                <th>Pemohon</th>
                                <th>Kantor</th>
                                <th>Jenis</th>
                                <th>Aksi Saya</th>
                                <th>Status Akhir</th>
                                <th>Tanggal Aksi</th>
                                <th class="text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $item)
                                @php $log = $item->approvalLogs->first(); @endphp
                                <tr>
                                    <td>
                                        <span class="font-mono text-xs font-medium text-slate-700">
                                            {{ $item->nomor_dokumen }}
                                        </span>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $item->form_type->label() }}</div>
                                    </td>
                                    <td class="font-medium">{{ $item->pemohon?->name }}</td>
                                    <td>{{ $item->kantor?->nama }}</td>
                                    <td>{{ $item->jenis_permohonan->label() }}</td>
                                    <td>
                                        @if ($log?->aksi === 'approved')
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium
                                                     text-green-700 bg-green-100 rounded-full px-2.5 py-1">
                                                ✓ Disetujui
                                            </span>
                                        @elseif ($log?->aksi === 'rejected')
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium
                                                     text-red-700 bg-red-100 rounded-full px-2.5 py-1">
                                                ✕ Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="{{ $item->status->badgeClass() }} text-xs px-2 py-0.5">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-sm text-slate-500">
                                        {{ $log?->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') ?? '—' }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('approval.atasan.show', $item) }}"
                                            class="btn-ghost btn-sm text-slate-500">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($riwayat->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">
                        {{ $riwayat->links() }}
                    </div>
                @endif
            @endif
        </div>

        <div>
            <a href="{{ route('approval.atasan.index') }}" class="btn-ghost btn-sm">
                ← Kembali ke Pending
            </a>
        </div>

    </div>
@endsection
