<div class="space-y-5">

    <div>
        <h2 class="text-lg font-semibold text-slate-900">
            Selamat datang, {{ $user->name }}
        </h2>
        <p class="text-sm text-slate-500 mt-0.5">
            {{ $user->jabatan_label }}
        </p>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="card card-body flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14
                             a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $pendingAsAtasan }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Menunggu sebagai Atasan</p>
                @if($pendingAsAtasan > 0)
                    <a href="{{ route('approval.dirut.index') }}"
                       class="text-xs text-amber-600 font-medium hover:underline">
                        Proses →
                    </a>
                @endif
            </div>
        </div>

        <div class="card card-body flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                             a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0
                             5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622
                             0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $pendingDirut }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Menunggu Persetujuan Dirut</p>
                @if($pendingDirut > 0)
                    <a href="{{ route('approval.dirut.index') }}"
                       class="text-xs text-purple-600 font-medium hover:underline">
                        Proses →
                    </a>
                @endif
            </div>
        </div>

        <div class="card card-body flex items-center gap-4">
            <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7
                             a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5
                             a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $totalPending }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Total Menunggu</p>
            </div>
        </div>

    </div>

    {{-- Riwayat approval terbaru --}}
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Riwayat Approval Anda</h3>
            <a href="{{ route('approval.dirut.index') }}"
               class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                Lihat pending
            </a>
        </div>

        @if($recentApproved->isEmpty())
            <div class="card-body text-center py-10">
                <p class="text-sm text-slate-500">Belum ada riwayat approval.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-auto-style">
                    <thead>
                        <tr>
                            <th>Nomor Dokumen</th>
                            <th>Pemohon</th>
                            <th>Kantor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApproved as $item)
                            <tr>
                                <td class="font-mono text-xs">
                                    {{ $item->nomor_dokumen }}
                                </td>
                                <td class="font-medium text-sm">
                                    {{ $item->pemohon?->name }}
                                </td>
                                <td class="text-sm">{{ $item->kantor?->nama }}</td>
                                <td>
                                    <span class="{{ $item->status->badgeClass() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>