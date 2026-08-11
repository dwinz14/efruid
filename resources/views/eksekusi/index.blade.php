@extends('layouts.app')

@section('title', 'Eksekusi IT')
@section('page-title', 'Eksekusi IT')

@section('content')
    <div class="space-y-4">

        {{-- Summary --}}
        <div class="card card-body">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-brand-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724
                                 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724
                                 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724
                                 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724
                                 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724
                                 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724
                                 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724
                                 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37
                                 .996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $pendingCount }}</p>
                    <p class="text-sm text-slate-500">Permohonan siap dieksekusi</p>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card card-body">
            <form method="GET" action="{{ route('eksekusi.index') }}" class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="label">Kantor</label>
                    <select name="kantor_id" class="input w-48">
                        <option value="">Semua Kantor</option>
                        @foreach ($kantors as $kantor)
                            <option value="{{ $kantor->id }}" @selected(request('kantor_id') == $kantor->id)>
                                {{ $kantor->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Jenis</label>
                    <select name="jenis" class="input w-40">
                        <option value="">Semua Jenis</option>
                        @foreach (\App\Enums\JenisPermohonan::cases() as $j)
                            <option value="{{ $j->value }}" @selected(request('jenis') === $j->value)>
                                {{ $j->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-secondary">Terapkan</button>
                @if (request()->hasAny(['kantor_id', 'jenis']))
                    <a href="{{ route('eksekusi.index') }}" class="btn-ghost">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    Daftar Permohonan Pending Eksekusi
                </h2>
            </div>

            @if ($pending->isEmpty())
                <div class="card-body text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-500 font-medium">Tidak ada permohonan pending</p>
                    <p class="text-sm text-slate-400 mt-1">Semua sudah dieksekusi</p>
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
                                <th>Disetujui</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pending as $item)
                                <tr>
                                    <td>
                                        <span class="font-mono text-xs font-medium text-slate-700">
                                            {{ $item->nomor_dokumen }}
                                        </span>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $item->form_type->label() }}
                                        </div>
                                    </td>
                                    <td class="font-medium">{{ $item->pemohon?->name }}</td>
                                    <td>{{ $item->kantor?->nama }}</td>
                                    <td>{{ $item->jenis_permohonan->label() }}</td>
                                    <td class="text-sm text-slate-500">
                                        {{ $item->updated_at->locale('id')->isoFormat('D MMM Y') }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('eksekusi.show', $item) }}" class="btn-primary btn-sm">
                                            Proses
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($pending->hasPages())
                    <div class="px-4 py-3 border-t border-surface-border">
                        {{ $pending->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
