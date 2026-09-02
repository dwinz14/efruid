<div class="space-y-5">

    <div>
        <h2 class="text-lg font-semibold text-slate-900">
            Dashboard Super Admin
        </h2>
        <p class="text-sm text-slate-500 mt-0.5">
            Overview seluruh sistem eFRUID
        </p>
    </div>

    {{-- Status summary grid --}}
    @php
        use App\Enums\StatusPermohonan;
        $statusCards = [
            [
                'status' => StatusPermohonan::PENDING_ATASAN,
                'color' => 'amber',
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            ],
            [
                'status' => StatusPermohonan::PENDING_DIRUT,
                'color' => 'purple',
                'icon' =>
                    'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            ],
            [
                'status' => StatusPermohonan::PENDING_IT,
                'color' => 'blue',
                'icon' =>
                    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
            ],
            [
                'status' => StatusPermohonan::EXECUTED,
                'color' => 'green',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'status' => StatusPermohonan::REJECTED,
                'color' => 'red',
                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'status' => StatusPermohonan::DRAFT,
                'color' => 'slate',
                'icon' =>
                    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            ],
        ];
        $colorMap = [
            'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
            'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
            'blue' => ['bg' => 'bg-brand-100', 'text' => 'text-brand-600'],
            'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
            'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
            'slate' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500'],
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach ($statusCards as $card)
            @php
                $c = $colorMap[$card['color']];
                $count = $statuses[$card['status']->value] ?? 0;
            @endphp
            <div class="card card-body text-center">
                <div
                    class="w-10 h-10 {{ $c['bg'] }} rounded-xl flex items-center
                            justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 {{ $c['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                    </svg>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ $count }}</p>
                <p class="text-xs text-slate-500 mt-0.5 leading-tight">
                    {{ $card['status']->label() }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Grafik 6 bulan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-semibold text-slate-800">
                Permohonan 6 Bulan Terakhir
            </h3>
        </div>
        <div class="card-body">
            @php $maxVal = max($chartData->pluck('count')->max(), 1); @endphp
            <div class="flex items-end gap-2 h-32">
                @foreach ($chartData as $item)
                    @php $height = round(($item['count'] / $maxVal) * 100); @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-xs font-medium text-slate-600">
                            {{ $item['count'] }}
                        </span>
                        <div class="w-full bg-brand-600 rounded-t-sm transition-all"
                            style="height: {{ max($height, 4) }}%"></div>
                        <span class="text-xs text-slate-400 whitespace-nowrap">
                            {{ $item['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Shortcut admin --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
            $shortcuts = [
                ['label' => 'Kelola User', 'route' => 'admin.users.index', 'color' => 'brand'],
                ['label' => 'Kelola Kantor', 'route' => 'admin.kantor.index', 'color' => 'brand'],
                ['label' => 'Kelola Jabatan', 'route' => 'admin.jabatan.index', 'color' => 'brand'],
                ['label' => 'Audit Log', 'route' => 'admin.audit-logs.index', 'color' => 'brand'],
            ];
        @endphp
        @foreach ($shortcuts as $s)
            <a href="{{ route($s['route']) }}"
                class="card card-body flex items-center gap-3
                      hover:shadow-card-hover transition-shadow">
                <div
                    class="w-8 h-8 bg-brand-100 rounded-lg flex items-center
                            justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-slate-700">{{ $s['label'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Aktivitas terbaru --}}
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.permohonan.index') }}"
                class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                Lihat semua
            </a>
        </div>

        @if ($recentPermohonan->isEmpty())
            <div class="card-body text-center py-10">
                <p class="text-sm text-slate-500">Belum ada aktivitas.</p>
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
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentPermohonan as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.permohonan.show', $item) }}"
                                        class="font-mono text-xs text-brand-600
                                              hover:underline">
                                        {{ $item->nomor_dokumen ?? '— Draft —' }}
                                    </a>
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
                                <td class="text-xs text-slate-500">
                                    {{ $item->updated_at->locale('id')->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
