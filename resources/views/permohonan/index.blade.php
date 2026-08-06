@extends('layouts.app')

@section('title', 'Permohonan Saya')
@section('page-title', 'Permohonan Saya')

@section('content')
    <div class="space-y-4">

        {{-- Header aksi --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">
                Daftar semua permohonan yang Anda buat
            </p>
            <a href="{{ route('permohonan.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Permohonan
            </a>
        </div>

        {{-- Filter --}}
        <div class="card card-body">
            <form method="GET" action="{{ route('permohonan.index') }}" class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="label">Filter Status</label>
                    <select name="status" class="input w-48">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s->value }}" @selected(request('status') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-secondary">Terapkan</button>
                @if (request()->hasAny(['status']))
                    <a href="{{ route('permohonan.index') }}" class="btn-ghost">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card">
            @if ($permohonan->isEmpty())
                <div class="card-body text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-slate-500 font-medium">Belum ada permohonan</p>
                    <p class="text-sm text-slate-400 mt-1">Klik "Buat Permohonan" untuk memulai</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto-style">
                        <thead>
                            <tr>
                                <th>Nomor Dokumen</th>
                                <th>Jenis</th>
                                <th>Kantor</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permohonan as $item)
                                <tr>
                                    <td>
                                        <span class="font-mono text-xs font-medium text-slate-700">
                                            {{ $item->nomor_dokumen ?? '— Draft —' }}
                                        </span>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $item->form_type->label() }}
                                        </div>
                                    </td>
                                    <td>{{ $item->jenis_permohonan->label() }}</td>
                                    <td>{{ $item->kantor?->nama ?? '—' }}</td>
                                    <td class="text-sm">
                                        {{ $item->tanggal_permohonan?->format('d/m/Y') ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="{{ $item->status->badgeClass() }}">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('permohonan.show', $item) }}" class="btn-secondary btn-sm">
                                                Detail
                                            </a>
                                            @if ($item->isDraft())
                                                <a href="{{ route('permohonan.edit', $item) }}" class="btn-ghost btn-sm">
                                                    Edit
                                                </a>
                                            @endif
                                            @if ($item->isCancellable())
                                                <form method="POST" action="{{ route('permohonan.cancel', $item) }}"
                                                    onsubmit="return confirm('Batalkan permohonan ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn-danger btn-sm">Batalkan</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($permohonan->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">
                        {{ $permohonan->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
