@extends('layouts.app')

@section('title', 'Riwayat Eksekusi Saya')
@section('page-title', 'Riwayat Eksekusi Saya')

@section('content')
    <div class="space-y-4">

        <div class="card card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $riwayat->total() }}</p>
                    <p class="text-sm text-slate-500">Total FRUID yang sudah Anda eksekusi</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">Riwayat Eksekusi</h2>
                <p class="text-xs text-slate-400 mt-0.5">Semua permohonan yang sudah Anda eksekusi di sistem USSI</p>
            </div>

            @if ($riwayat->isEmpty())
                <div class="card-body text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-500 font-medium">Belum ada eksekusi</p>
                    <p class="text-sm text-slate-400 mt-1">Permohonan yang sudah Anda eksekusi akan muncul di sini</p>
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
                                <th>Tanggal Eksekusi</th>
                                <th>PDF</th>
                                <th class="text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $item)
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
                                    <td class="text-sm text-slate-500">
                                        {{ $item->updated_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                    </td>
                                    <td>
                                        @if ($item->pdf_path)
                                            <a href="{{ route('permohonan.pdf', $item) }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-xs text-brand-600 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2
                                                               2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1
                                                               0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Download
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">Generating...</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('eksekusi.show', $item) }}"
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
            <a href="{{ route('eksekusi.index') }}" class="btn-ghost btn-sm">
                ← Kembali ke Pending Eksekusi
            </a>
        </div>

    </div>
@endsection
