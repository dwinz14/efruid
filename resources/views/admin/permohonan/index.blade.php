@extends('layouts.app')

@section('title', 'Semua Permohonan')
@section('page-title', 'Semua Permohonan')

@section('content')
    <div class="space-y-4">

        {{-- Filter --}}
        <div class="card card-body">
            <form method="GET" action="{{ route('admin.permohonan.index') }}" class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="label">Cari</label>
                    <input name="search" type="text" value="{{ request('search') }}" class="input w-52"
                        placeholder="Nomor dokumen, nama, NIK">
                </div>
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="input w-44">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s->value }}" @selected(request('status') === $s->value)>
                                {{ $s->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Kantor</label>
                    <select name="kantor_id" class="input w-40">
                        <option value="">Semua Kantor</option>
                        @foreach ($kantor as $k)
                            <option value="{{ $k->id }}" @selected(request('kantor_id') == $k->id)>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-secondary">Terapkan</button>
                @if (request()->hasAny(['search', 'status', 'kantor_id']))
                    <a href="{{ route('admin.permohonan.index') }}" class="btn-ghost">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    Total {{ number_format($permohonan->total()) }} permohonan
                </h2>
            </div>
            @if ($permohonan->isEmpty())
                <div class="card-body text-center py-12">
                    <p class="text-slate-500">Tidak ada permohonan.</p>
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
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permohonan as $item)
                                <tr>
                                    <td>
                                        <span class="font-mono text-xs font-medium">
                                            {{ $item->nomor_dokumen ?? '— Draft —' }}
                                        </span>
                                        <div class="text-xs text-slate-400">
                                            {{ $item->form_type->label() }}
                                        </div>
                                    </td>
                                    <td class="font-medium text-sm">
                                        {{ $item->pemohon?->name }}
                                        <div class="text-xs text-slate-400 font-mono">
                                            {{ $item->nik_pemohon }}
                                        </div>
                                    </td>
                                    <td class="text-sm">{{ $item->kantor?->nama }}</td>
                                    <td class="text-sm">
                                        {{ $item->jenis_permohonan->label() }}
                                    </td>
                                    <td>
                                        <span class="{{ $item->status->badgeClass() }}">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-slate-500">
                                        {{ $item->created_at->locale('id')->isoFormat('D MMM Y') }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.permohonan.show', $item) }}"
                                            class="btn-secondary btn-sm">Detail</a>
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
