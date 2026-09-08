<div class="space-y-6">

    {{-- ── 1. Greeting Hero Banner ── --}}
    <div
        class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-sm animate-fade-up relative overflow-hidden">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        Direktur Utama & Executive Approver
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                    Selamat datang, {{ $user->name }} 👋
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 flex items-center gap-2 flex-wrap font-medium">
                    <span class="inline-flex items-center gap-1 text-slate-700">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ $user->jabatan_label }}
                    </span>
                    <span>&bull;</span>
                    <span class="inline-flex items-center gap-1 text-slate-700">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        BPR Artha Pamenang
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('approval.dirut.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm shadow-brand-500/30 hover:bg-brand-700 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Buka Antrean Approval
                </a>
                <a href="{{ route('approval.dirut.riwayat') }}"
                    class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl transition-colors">
                    Riwayat Persetujuan
                </a>
            </div>
        </div>

        {{-- Background Glow Accent --}}
        <div class="absolute -right-10 -top-10 w-44 h-44 bg-purple-500/10 rounded-full blur-3xl pointer-events-none">
        </div>
    </div>

    {{-- ── 2. Action Required Alert Callout (Jika ada permohonan pending) ── --}}
    @if ($totalPending > 0)
        <div class="flex items-start gap-4 p-5 rounded-2xl bg-amber-50 border border-amber-200/80 shadow-sm animate-fade-up"
            style="animation-delay: 0.05s;" role="alert">
            <div class="bg-amber-100 p-2.5 rounded-xl flex-shrink-0 text-amber-700 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-amber-900">Persetujuan Dokumen Diperlukan</h3>
                <p class="text-xs sm:text-sm text-amber-800 mt-0.5 leading-relaxed">
                    Terdapat <strong class="text-amber-950">{{ $totalPending }} permohonan</strong> yang menunggu
                    persetujuan anda ({{ $pendingAsAtasan }} sebagai Atasan, {{ $pendingDirut }} permohonan Rangkap
                    Jabatan).
                </p>
                <a href="{{ route('approval.dirut.index') }}"
                    class="inline-flex items-center gap-1.5 mt-2.5 text-xs font-bold text-amber-900 hover:text-amber-950 underline transition-colors">
                    Proses Persetujuan Sekarang &rarr;
                </a>
            </div>
        </div>
    @endif

    {{-- ── 3. Metric Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade-up" style="animation-delay: 0.1s;">

        {{-- Card: Menunggu sebagai Atasan --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Persetujuan Sebagai Atasan</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1.5">{{ $pendingAsAtasan }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Bawahan langsung</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
        </div>

        {{-- Card: Persetujuan Dirut (Rangkap) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-purple-600 uppercase tracking-wider">Persetujuan Direktur Utama</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-purple-700 mt-1.5">{{ $pendingDirut }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Permohonan rangkap jabatan</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-purple-400 to-purple-600"></div>
        </div>

        {{-- Card: Total Pending --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-brand-600 uppercase tracking-wider">Total Menunggu Keputusan</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-700 mt-1.5">{{ $totalPending }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Seluruh antrean anda</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 flex-shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-brand-500 to-brand-700"></div>
        </div>

    </div>

    {{-- ── 4. Riwayat Approval Terbaru ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
        style="animation-delay: 0.15s;">
        <div
            class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Riwayat Persetujuan Terbaru anda</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Dokumen FRUID yang telah anda setujui sebelumnya</p>
                </div>
            </div>

            <a href="{{ route('approval.dirut.riwayat') }}"
                class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-800 transition-colors">
                Lihat Semua Riwayat &rarr;
            </a>
        </div>

        @if ($recentApproved->isEmpty())
            <div class="p-12 text-center">
                <div
                    class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100 text-slate-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum Ada Riwayat Approval</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Dokumen FRUID yang telah anda setujui akan tercatat otomatis di sini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-slate-50/80 border-b border-slate-200 text-[11px] uppercase font-bold text-slate-500 tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Dokumen</th>
                            <th class="px-4 py-3.5">Pemohon</th>
                            <th class="px-4 py-3.5">Kantor Cabang</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentApproved as $item)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                            {{ $item->nomor_dokumen }}
                                        </span>
                                        @if ($item->form_type->value === 'rangkap')
                                            <span
                                                class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                                Rangkap
                                            </span>
                                        @else
                                            <span
                                                class="text-[10px] font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                                                Reguler
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0 border border-slate-200">
                                            {{ strtoupper(substr($item->pemohon?->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">
                                                {{ $item->pemohon?->name ?? '—' }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">
                                                {{ $item->pemohon?->jabatan_label ?? 'Staff' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs font-medium text-slate-700">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate">{{ $item->kantor?->nama ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="{{ $item->status->badgeClass() }} text-xs px-2.5 py-0.5">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('approval.dirut.show', $item) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                        Lihat
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
