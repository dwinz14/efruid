@extends('layouts.app')

@section('title', 'Eksekusi IT')
@section('page-title', 'Eksekusi IT')

@section('content')
    <div class="space-y-4">

        {{-- Summary --}}
        <div class="card card-body">
            <div class="flex items-center gap-6 flex-wrap">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94
                                                       3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724
                                                       1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572
                                                       1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31
                                                       -.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724
                                                       1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $pendingCount }}</p>
                        <p class="text-sm text-slate-500">Total menunggu eksekusi</p>
                    </div>
                </div>

                @if ($myClaimedCount > 0)
                    <div class="flex items-center gap-3 pl-6 border-l border-surface-border">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-blue-700">{{ $myClaimedCount }}</p>
                            <p class="text-sm text-slate-500">Saya sedang kerjakan</p>
                        </div>
                    </div>
                @endif
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
                <h2 class="text-sm font-semibold text-slate-800">Daftar Permohonan Pending Eksekusi</h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    "Ambil" untuk mengklaim, lalu "Eksekusi" setelah selesai di USSI.
                </p>
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
                                <th>Status Klaim</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pending as $item)
                                @php $myId = auth()->id(); @endphp
                                <tr class="{{ $item->isClaimedBy($myId) ? 'bg-blue-50/40' : '' }}">
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

                                    {{-- Status Klaim --}}
                                    <td>
                                        @if (!$item->isClaimed())
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium
                                                         text-green-700 bg-green-100 rounded-full px-2.5 py-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                Tersedia
                                            </span>
                                        @elseif ($item->isClaimedBy($myId))
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium
                                                         text-blue-700 bg-blue-100 rounded-full px-2.5 py-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                Saya kerjakan
                                            </span>
                                        @else
                                            <div>
                                                <span
                                                    class="inline-flex items-center gap-1 text-xs font-medium
                                                             text-amber-700 bg-amber-100 rounded-full px-2.5 py-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Diambil
                                                </span>
                                                <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[140px]">
                                                    {{ $item->executor?->name }}
                                                </p>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if (!$item->isClaimed())
                                                {{-- Tersedia: tombol Ambil --}}
                                                <form action="{{ route('eksekusi.claim', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-primary btn-sm">
                                                        Ambil
                                                    </button>
                                                </form>
                                            @elseif ($item->isClaimedBy($myId))
                                                {{-- Saya yang ambil: Eksekusi + Lepas --}}
                                                <a href="{{ route('eksekusi.show', $item) }}" class="btn-primary btn-sm">
                                                    Eksekusi
                                                </a>
                                                <form action="{{ route('eksekusi.unclaim', $item) }}" method="POST"
                                                    onsubmit="return confirm('Lepas klaim permohonan ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn-ghost btn-sm text-slate-500">
                                                        Lepas
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Diambil orang lain: hanya Lihat --}}
                                                <a href="{{ route('eksekusi.show', $item) }}"
                                                    class="btn-ghost btn-sm text-slate-500">
                                                    Lihat
                                                </a>
                                            @endif
                                        </div>
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
