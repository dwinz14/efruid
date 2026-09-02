<div class="space-y-5">

    <div>
        <h2 class="text-lg font-semibold text-slate-900">
            Selamat datang, {{ $user->name }}
        </h2>
        <p class="text-sm text-slate-500 mt-0.5">Staff IT</p>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="card card-body flex items-center gap-4">
            <div
                class="w-12 h-12 bg-amber-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $pendingIt }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Siap Dieksekusi</p>
                @if ($pendingIt > 0)
                    <a href="{{ route('eksekusi.index') }}" class="text-xs text-amber-600 font-medium hover:underline">
                        Eksekusi →
                    </a>
                @endif
            </div>
        </div>

        <div class="card card-body flex items-center gap-4">
            <div
                class="w-12 h-12 bg-green-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $executedToday }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Dieksekusi Hari Ini</p>
            </div>
        </div>

        <div class="card card-body flex items-center gap-4">
            <div
                class="w-12 h-12 bg-brand-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002
                             2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10
                             m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2
                             a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $executedThisMonth }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Bulan Ini</p>
            </div>
        </div>

    </div>

    {{-- Permohonan pending IT --}}
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">
                Antrian Eksekusi
            </h3>
            <a href="{{ route('eksekusi.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                Lihat semua
            </a>
        </div>

        @if ($recentPending->isEmpty())
            <div class="card-body text-center py-10">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-slate-500">
                    Semua permohonan sudah dieksekusi
                </p>
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
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentPending as $item)
                            <tr>
                                <td class="font-mono text-xs">
                                    {{ $item->nomor_dokumen }}
                                </td>
                                <td class="font-medium text-sm">
                                    {{ $item->pemohon?->name }}
                                </td>
                                <td class="text-sm">{{ $item->kantor?->nama }}</td>
                                <td class="text-sm">
                                    {{ $item->jenis_permohonan->label() }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('eksekusi.show', $item) }}" class="btn-primary btn-sm">Proses</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
