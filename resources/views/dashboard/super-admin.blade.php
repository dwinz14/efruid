<div class="space-y-6">

    {{-- ── 1. Header & System Status Banner ── --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-sm animate-fade-up relative overflow-hidden">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                        <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                        Super Administrator
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        {{ $securityKpi['online_now'] }} Pengguna Aktif
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                    Pusat Kendali Sistem eFRUID
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Ringkasan menyeluruh alur formulir registrasi user ID, analitik permohonan, dan keamanan sistem BPR Artha Pamenang.
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('admin.permohonan.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm shadow-brand-500/30 hover:bg-brand-700 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Semua Permohonan
                </a>
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Audit Log
                </a>
            </div>
        </div>

        {{-- Decorative Glow Background --}}
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    {{-- ── 2. Status Permohonan Pipeline (6 Grid Cards) ── --}}
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
                'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            ],
            [
                'status' => StatusPermohonan::PENDING_IT,
                'color' => 'blue',
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
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
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            ],
        ];

        $colorMap = [
            'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'bar' => 'from-amber-400 to-amber-500'],
            'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'bar' => 'from-purple-400 to-purple-600'],
            'blue' => ['bg' => 'bg-brand-50', 'text' => 'text-brand-600', 'border' => 'border-brand-100', 'bar' => 'from-brand-500 to-brand-700'],
            'green' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'bar' => 'from-emerald-400 to-emerald-600'],
            'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100', 'bar' => 'from-red-400 to-red-600'],
            'slate' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'border' => 'border-slate-200', 'bar' => 'from-slate-400 to-slate-500'],
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 animate-fade-up" style="animation-delay: 0.05s;">
        @foreach ($statusCards as $card)
            @php
                $c = $colorMap[$card['color']];
                $count = $statuses[$card['status']->value] ?? 0;
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex flex-col items-center text-center">
                    <div class="w-10 h-10 {{ $c['bg'] }} {{ $c['border'] }} border rounded-xl flex items-center justify-center mb-2.5 flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 {{ $c['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-900 leading-none">{{ $count }}</p>
                    <p class="text-[11px] font-bold text-slate-500 mt-1.5 leading-tight">
                        {{ $card['status']->label() }}
                    </p>
                </div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r {{ $c['bar'] }}"></div>
            </div>
        @endforeach
    </div>

    {{-- ── 3. Tren Permohonan & Quick Shortcuts Grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-up" style="animation-delay: 0.1s;">

        {{-- Left 2 Cols: Grafik Tren 6 Bulan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Tren Permohonan (6 Bulan Terakhir)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Jumlah formulir diajukan per bulan (di luar draft/batal)</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @php $maxVal = max($chartData->pluck('count')->max(), 1); @endphp
                <div class="flex items-end gap-3 sm:gap-6 h-44 pt-4">
                    @foreach ($chartData as $item)
                        @php $height = round(($item['count'] / $maxVal) * 100); @endphp
                        <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 group-hover:bg-brand-50 group-hover:text-brand-700 px-2 py-0.5 rounded transition-colors">
                                {{ $item['count'] }}
                            </span>
                            <div class="w-full bg-slate-100 rounded-t-xl overflow-hidden flex items-end h-full">
                                <div class="w-full bg-gradient-to-t from-brand-700 to-brand-500 group-hover:from-brand-600 group-hover:to-brand-400 rounded-t-xl transition-all duration-300 shadow-xs"
                                    style="height: {{ max($height, 6) }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-400 whitespace-nowrap mt-1">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-400">
                <span>Rata-rata volume dokumen bulanan stabil</span>
                <span class="font-bold text-slate-600">Total Periode: {{ $chartData->sum('count') }} Dokumen</span>
            </div>
        </div>

        {{-- Right 1 Col: Shortcut Kelola Master Data --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Pintasan Manajemen</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Akses cepat modul master data</p>
                </div>
            </div>

            <div class="p-4 space-y-2.5">
                @php
                    $shortcuts = [
                        ['label' => 'Kelola Pengguna', 'desc' => 'Akun, NIK & Hak Akses', 'route' => 'admin.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['label' => 'Kantor Cabang / Kas', 'desc' => 'Daftar entitas kantor', 'route' => 'admin.kantor.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['label' => 'Daftar Jabatan', 'desc' => 'Hierarki posisi pegawai', 'route' => 'admin.jabatan.index', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['label' => 'Audit Log Forensik', 'desc' => 'Jejak rekam aktivitas user', 'route' => 'admin.audit-logs.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ];
                @endphp
                @foreach ($shortcuts as $s)
                    <a href="{{ route($s['route']) }}"
                        class="flex items-center justify-between p-3 rounded-xl border border-slate-200/80 hover:border-brand-300 bg-slate-50/50 hover:bg-brand-50/30 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 group-hover:text-brand-600 flex items-center justify-center flex-shrink-0 shadow-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-brand-700 transition-colors">{{ $s['label'] }}</p>
                                <p class="text-[11px] text-slate-400">{{ $s['desc'] }}</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-600 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endforeach
            </div>

            <div class="p-4 pt-0">
                <a href="{{ route('admin.security.index') }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Buka Security Center &rarr;
                </a>
            </div>
        </div>

    </div>

    {{-- ── 4. Aktivitas Permohonan Terbaru ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up" style="animation-delay: 0.15s;">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Aktivitas Permohonan Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar permohonan terkini yang masuk ke sistem</p>
                </div>
            </div>

            <a href="{{ route('admin.permohonan.index') }}"
                class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-800 transition-colors">
                Lihat Semua Permohonan &rarr;
            </a>
        </div>

        @if ($recentPermohonan->isEmpty())
            <div class="p-12 text-center">
                <p class="text-sm font-medium text-slate-400">Belum ada aktivitas permohonan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 border-b border-slate-200 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Dokumen</th>
                            <th class="px-4 py-3.5">Pemohon</th>
                            <th class="px-4 py-3.5">Kantor Cabang</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentPermohonan as $item)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-5 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ $item->nomor_dokumen ?? '— Draft —' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 border border-slate-200">
                                            {{ strtoupper(substr($item->pemohon?->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $item->pemohon?->name ?? '—' }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $item->pemohon?->jabatan_label ?? 'Staff' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs font-medium text-slate-700">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate">{{ $item->kantor?->nama ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="{{ $item->status->badgeClass() }} text-xs px-2.5 py-0.5">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-500">
                                    {{ $item->updated_at->locale('id')->diffForHumans() }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.permohonan.show', $item) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-lg transition-colors">
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

    {{-- ── 5. Security Command Overview ── --}}
    <div class="space-y-4 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Ringkasan Keamanan Sistem (Security KPI)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Monitoring integritas sesi login dan status proteksi akun</p>
            </div>
            <a href="{{ route('admin.security.index') }}"
                class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-800 transition-colors">
                Security Center &rarr;
            </a>
        </div>

        {{-- 6 Security KPI Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
            @php
                $secItems = [
                    [
                        'label' => 'Online Sekarang',
                        'value' => $securityKpi['online_now'],
                        'color' => 'green',
                        'link' => route('admin.sessions.index'),
                    ],
                    [
                        'label' => 'Gagal Login Hari Ini',
                        'value' => $securityKpi['failed_today'],
                        'color' => $securityKpi['failed_today'] > 0 ? 'amber' : 'slate',
                        'link' => route('admin.security.login-history'),
                    ],
                    [
                        'label' => 'Akun Terkunci',
                        'value' => $securityKpi['locked_accounts'],
                        'color' => $securityKpi['locked_accounts'] > 0 ? 'red' : 'slate',
                        'link' => route('admin.users.index', ['status' => 'locked']),
                    ],
                    [
                        'label' => 'Akun Suspended',
                        'value' => $securityKpi['suspended_accounts'],
                        'color' => $securityKpi['suspended_accounts'] > 0 ? 'red' : 'slate',
                        'link' => route('admin.users.index', ['status' => 'suspended']),
                    ],
                    [
                        'label' => 'Pending Verifikasi',
                        'value' => $securityKpi['pending_verify'],
                        'color' => $securityKpi['pending_verify'] > 0 ? 'amber' : 'slate',
                        'link' => route('admin.users.pending'),
                    ],
                    [
                        'label' => 'Pengguna Aktif',
                        'value' => $securityKpi['total_users'],
                        'color' => 'brand',
                        'link' => route('admin.users.index'),
                    ],
                ];

                $secColor = [
                    'green' => ['num' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200'],
                    'amber' => ['num' => 'text-amber-700', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200'],
                    'red' => ['num' => 'text-red-700', 'bg' => 'bg-red-50', 'border' => 'border-red-200'],
                    'brand' => ['num' => 'text-brand-700', 'bg' => 'bg-brand-50', 'border' => 'border-brand-200'],
                    'slate' => ['num' => 'text-slate-700', 'bg' => 'bg-white', 'border' => 'border-slate-200/80'],
                ];
            @endphp
            @foreach ($secItems as $item)
                @php $c = $secColor[$item['color']]; @endphp
                <a href="{{ $item['link'] }}"
                    class="rounded-2xl p-4 text-center border {{ $c['border'] }} {{ $c['bg'] }} shadow-xs hover:shadow-md transition-all group">
                    <p class="text-2xl font-extrabold {{ $c['num'] }} group-hover:scale-105 transition-transform">{{ $item['value'] }}</p>
                    <p class="text-[11px] font-bold text-slate-500 mt-1 leading-tight">{{ $item['label'] }}</p>
                </a>
            @endforeach
        </div>

        {{-- Live Security Events Stream --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    <h3 class="text-sm font-bold text-slate-800">Event Keamanan & Otentikasi Terbaru</h3>
                </div>
                <a href="{{ route('admin.security.login-history') }}"
                    class="text-xs font-bold text-brand-600 hover:text-brand-800 transition-colors">
                    Lihat Semua Log &rarr;
                </a>
            </div>

            @if ($recentSecurityEvents->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-xs text-slate-400">Tidak ada event keamanan terkini.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($recentSecurityEvents as $event)
                        @php
                            $isAlert = in_array($event->aksi->value, [
                                \App\Enums\AksiAudit::USER_LOGIN_FAILED->value,
                                \App\Enums\AksiAudit::USER_ACCOUNT_LOCKED->value,
                                \App\Enums\AksiAudit::USER_SUSPENDED->value,
                                \App\Enums\AksiAudit::USER_FORCE_LOGOUT->value,
                            ]);
                        @endphp
                        <div class="px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $isAlert ? 'bg-red-500' : 'bg-emerald-500' }}"></div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $event->aksi->label() }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ $event->user?->name ?? 'Sistem' }}
                                        @if ($event->ip_address)
                                            &middot; <span class="font-mono text-slate-500">{{ $event->ip_address }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <span class="text-[11px] font-medium text-slate-400 whitespace-nowrap flex-shrink-0">
                                {{ $event->created_at->locale('id')->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
