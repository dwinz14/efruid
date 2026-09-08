@extends('layouts.app')

@section('title', 'Permohonan Saya')
@section('page-title', 'Permohonan Saya')

@section('content')
    <div class="space-y-6">

        {{-- Deskripsi dan Tombol Aksi Atas --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-brand-50 rounded-xl border border-brand-100 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Daftar Permohonan</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Kelola dan pantau status permohonan yang telah Anda buat
                    </p>
                </div>
            </div>
            <a href="{{ route('permohonan.create') }}"
                class="group inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-brand-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm shadow-brand-500/20 transition-all duration-200 hover:-translate-y-px hover:bg-brand-700 hover:shadow-md hover:shadow-brand-500/30 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-2 active:translate-y-0 active:scale-[0.98]">
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:scale-110" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>

                <span>Buat Permohonan</span>
            </a>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/60 shadow-sm animate-fade-up"
            style="animation-delay: 0.1s;">
            <form method="GET" action="{{ route('permohonan.index') }}"
                class="flex flex-col sm:flex-row sm:items-end gap-4">

                <div class="flex-1 sm:max-w-xs relative">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">
                        Filter Status
                    </label>
                    <div class="relative">
                        <select name="status"
                            class="block w-full appearance-none rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm font-medium text-slate-700 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors cursor-pointer">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $s)
                                <option value="{{ $s->value }}" @selected(request('status') === $s->value)>
                                    {{ $s->label() }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 shadow-sm focus:ring-2 focus:ring-slate-500 focus:ring-offset-1 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan
                    </button>
                    @if (request()->hasAny(['status']) && request('status') != '')
                        <a href="{{ route('permohonan.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden animate-fade-up"
            style="animation-delay: 0.2s;">
            @if ($permohonan->isEmpty())
                <div class="px-6 py-20 flex flex-col items-center justify-center text-center bg-slate-50/30">
                    <div
                        class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-5 border border-slate-200 shadow-sm">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-700 mb-1">Belum ada permohonan</h4>
                    @if (request('status'))
                        <p class="text-sm text-slate-500 max-w-sm mb-4">Tidak ada permohonan yang sesuai dengan filter yang
                            Anda pilih.</p>
                        <a href="{{ route('permohonan.index') }}"
                            class="text-sm font-semibold text-brand-600 hover:text-brand-800">Bersihkan filter</a>
                    @else
                        <p class="text-sm text-slate-500 max-w-sm">Anda belum memiliki riwayat permohonan. Klik "Buat
                            Permohonan" untuk memulai pengajuan baru.</p>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80">
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                    Info Dokumen</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                    Jenis & Kantor</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($permohonan as $item)
                                <tr class="hover:bg-slate-50/80 transition-colors group">

                                    {{-- Kolom Info Dokumen --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-200/60">
                                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block font-mono text-sm font-bold text-slate-800">
                                                    {{ $item->nomor_dokumen ?? '— Draft —' }}
                                                </span>
                                                <span class="block text-xs font-medium text-slate-500 mt-0.5">
                                                    {{ $item->form_type->label() }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Jenis & Kantor --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm font-semibold text-slate-700">
                                                {{ $item->jenis_permohonan->label() }}
                                            </span>
                                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $item->kantor?->nama ?? '—' }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-slate-600">
                                            {{ $item->tanggal_permohonan?->format('d/m/Y') ?? '—' }}
                                        </span>
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $item->status->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Tombol Detail --}}
                                            <a href="{{ route('permohonan.show', $item) }}"
                                                class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-bold text-brand-700 bg-brand-50 rounded-lg hover:bg-brand-600 hover:text-white transition-colors focus:ring-2 focus:ring-brand-500 focus:ring-offset-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail
                                            </a>

                                            {{-- Tombol Edit (Hanya Draft) --}}
                                            @if ($item->isDraft())
                                                <a href="{{ route('permohonan.edit', $item) }}"
                                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-700 hover:text-white transition-colors focus:ring-2 focus:ring-slate-500 focus:ring-offset-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                            @endif

                                            {{-- Tombol Batalkan --}}
                                            @if ($item->isCancellable())
                                                <form method="POST" action="{{ route('permohonan.cancel', $item) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permohonan ini?')"
                                                    class="inline-block">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Navigasi Paginasi --}}
                @if ($permohonan->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $permohonan->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection
