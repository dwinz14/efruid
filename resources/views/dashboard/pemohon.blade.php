@php
    use App\Enums\StatusPermohonan;
    $statusConfig = [
        StatusPermohonan::DRAFT->value => [
            'label' => 'Draft',
            'color' => 'text-slate-600',
            'bg' => 'bg-slate-100',
            'icon' =>
                'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        ],
        StatusPermohonan::PENDING_ATASAN->value => [
            'label' => 'Menunggu Atasan',
            'color' => 'text-amber-600',
            'bg' => 'bg-amber-50',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        StatusPermohonan::PENDING_DIRUT->value => [
            'label' => 'Menunggu Dirut',
            'color' => 'text-purple-600',
            'bg' => 'bg-purple-50',
            'icon' =>
                'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        ],
        StatusPermohonan::PENDING_IT->value => [
            'label' => 'Menunggu IT',
            'color' => 'text-brand-600',
            'bg' => 'bg-brand-50',
            'icon' =>
                'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        ],
        StatusPermohonan::EXECUTED->value => [
            'label' => 'Selesai',
            'color' => 'text-emerald-600',
            'bg' => 'bg-emerald-50',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        StatusPermohonan::REJECTED->value => [
            'label' => 'Ditolak',
            'color' => 'text-red-600',
            'bg' => 'bg-red-50',
            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        StatusPermohonan::CANCELLED->value => [
            'label' => 'Dibatalkan',
            'color' => 'text-slate-500',
            'bg' => 'bg-slate-100',
            'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
        ],
    ];
@endphp

<div class="space-y-6">

    {{-- Greeting Banner --}}
    <div
        class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/60 shadow-sm animate-fade-up relative overflow-hidden">
        {{-- Decorative background element --}}
        <div
            class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gradient-to-br from-brand-100 to-brand-50 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Selamat datang, {{ $user->name }} 👋
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ $user->jabatan_label }}
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-semibold">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ $user->kantor?->label }}
                    </span>
                </div>
            </div>

            <a href="{{ route('permohonan.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-brand-500/30 hover:bg-brand-700 hover:shadow-brand-500/40 focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-all active:scale-95 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Permohonan
            </a>
        </div>
    </div>

    {{-- Pending sebagai atasan (jika ada) --}}
    @if ($pendingAsAtasan > 0)
        <div class="flex items-start gap-4 p-5 rounded-2xl bg-amber-50 border border-amber-200/60 shadow-sm animate-fade-up"
            style="animation-delay: 0.1s;" role="alert">
            <div class="bg-amber-100/80 p-2.5 rounded-full flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <h3 class="text-sm font-bold text-amber-800">Tindakan Diperlukan</h3>
                <p class="text-sm text-amber-700 mt-1">
                    Ada <span class="font-bold text-amber-900">{{ $pendingAsAtasan }} permohonan</span> yang menunggu
                    persetujuan anda sebagai atasan.
                </p>
                <a href="{{ route('approval.atasan.index') }}"
                    class="inline-flex items-center gap-1 mt-3 text-sm font-semibold text-amber-800 hover:text-amber-900 transition-colors group">
                    Proses sekarang
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    @endif

    {{-- Statistik status --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 animate-fade-up" style="animation-delay: 0.2s;">
        @foreach ($statusConfig as $statusVal => $config)
            @php $count = $statuses[$statusVal] ?? 0; @endphp
            <div
                class="bg-white rounded-2xl p-4 border border-slate-200/60 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 group cursor-default">
                <div
                    class="w-12 h-12 {{ $config['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 {{ $config['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider truncate mb-1">
                        {{ $config['label'] }}
                    </p>
                    <p class="text-2xl font-bold {{ $config['color'] }} leading-none">
                        {{ $count }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Permohonan terbaru --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden animate-fade-up"
        style="animation-delay: 0.3s;">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-200/50">
                    <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Permohonan Terbaru</h3>
            </div>
            <a href="{{ route('permohonan.index') }}"
                class="text-sm text-brand-600 hover:text-brand-800 font-semibold transition-colors">
                Lihat semua &rarr;
            </a>
        </div>

        @if ($recentPermohonan->isEmpty())
            <div class="px-6 py-16 flex flex-col items-center justify-center text-center bg-slate-50/30">
                <div
                    class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-5 border border-slate-200 shadow-sm">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4 class="text-base font-bold text-slate-700 mb-1">Belum ada permohonan</h4>
                <p class="text-sm text-slate-500 max-w-sm">
                    Anda belum membuat permohonan apapun. Klik tombol <span class="font-semibold text-slate-700">Buat
                        Permohonan</span> di atas untuk memulai.
                </p>
            </div>
        @else
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor
                                Dokumen</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis
                                Permohonan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($recentPermohonan as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-slate-700">
                                        {{ $item->nomor_dokumen ?? '— Draft —' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-brand-500"></div>
                                        <span class="text-sm font-medium text-slate-700">
                                            {{ $item->jenis_permohonan->label() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Menggunakan badgeClass bawaan dari enum jika ada, atau fallback sederhana --}}
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->status->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                    {{ $item->created_at->locale('id')->isoFormat('D MMM Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('permohonan.show', $item) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-brand-600 bg-brand-50 rounded-lg hover:bg-brand-600 hover:text-white transition-colors focus:ring-2 focus:ring-brand-500 focus:ring-offset-1">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
