{{--
    Partial: Verification Panel (Premium Modern UI/UX)
    Digunakan di: show.blade.php (Desktop Sidebar & Mobile Drawer/Tab)
--}}

<div class="flex flex-col h-full bg-white text-slate-800" x-data="{
    copiedText: '',
    copy(text, label) {
        if (!navigator.clipboard) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            this.copiedText = label;
            setTimeout(() => this.copiedText = '', 2000);
            return;
        }
        navigator.clipboard.writeText(text).then(() => {
            this.copiedText = label;
            setTimeout(() => this.copiedText = '', 2000);
        }).catch(() => {
            this.copiedText = label;
            setTimeout(() => this.copiedText = '', 2000);
        });
    }
}">

    {{-- ── 1. Panel Header (Brand Security Hero) ────────────────────────── --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-800 text-white p-5 md:p-6 shrink-0 shadow-md">
        {{-- Background Geometric Glow --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-400/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-36 h-36 bg-teal-300/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center shadow-inner shrink-0 text-emerald-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-extrabold tracking-wider uppercase text-white">Dokumen Sah & Asli</h2>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-400/20 text-emerald-200 border border-emerald-400/30">
                                RESMI
                            </span>
                        </div>
                        <p class="text-xs text-emerald-100/90 mt-0.5 leading-snug">Sistem Verifikasi Digital eFRUID</p>
                    </div>
                </div>

                {{-- Bank Badge --}}
                <div class="hidden sm:block text-right shrink-0">
                    <span class="text-[10px] font-semibold text-emerald-200/80 block uppercase tracking-wider">BPR Artha</span>
                    <span class="text-[11px] font-bold text-white block">Pamenang</span>
                </div>
            </div>

            {{-- Realtime Verification Status Indicator --}}
            <div class="mt-4 bg-white/10 border border-white/15 backdrop-blur-md rounded-xl py-2 px-3 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                    </span>
                    <span class="text-[11px] font-medium text-emerald-50 truncate">Tervalidasi Real-Time</span>
                </div>
                <span class="text-[10px] font-mono text-emerald-200/90 bg-emerald-950/40 px-2 py-0.5 rounded border border-white/10 shrink-0">
                    {{ count($stamps) }} Tanda Tangan Digital
                </span>
            </div>
        </div>
    </div>

    {{-- ── 2. Panel Body (Scrollable Details) ───────────────────────────── --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar p-5 md:p-6 space-y-6">

        {{-- Copy Feedback Notification Toast --}}
        <div x-show="copiedText !== ''" x-transition.opacity.duration.200ms
            class="sticky top-0 z-30 bg-slate-900 text-white text-xs font-semibold py-2 px-4 rounded-xl shadow-xl flex items-center justify-center gap-2 mb-3">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span x-text="copiedText + ' berhasil disalin!'"></span>
        </div>

        {{-- ── Section A: Informasi Utama Dokumen ────────────────────────── --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Informasi Dokumen
                </h3>
            </div>

            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-3.5 shadow-sm">
                {{-- Nomor Dokumen with Quick Copy --}}
                <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-200/60">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Nomor Dokumen</p>
                        <p class="text-[13px] font-mono font-bold text-emerald-700 break-all leading-tight select-all">
                            {{ $permohonan->nomor_dokumen }}
                        </p>
                    </div>
                    <button type="button" @click="copy('{{ $permohonan->nomor_dokumen }}', 'Nomor Dokumen')"
                        title="Salin Nomor Dokumen"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 active:scale-95 transition-all shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                        </svg>
                    </button>
                </div>

                {{-- Grid Details --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    {{-- Pemohon --}}
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Pemohon</p>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $permohonan->nama_pemohon }}</p>
                        <p class="text-[11px] text-slate-500">{{ $permohonan->jabatan_pemohon }}</p>
                    </div>

                    {{-- Kantor --}}
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Kantor Unit</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $permohonan->kantor?->nama ? ($permohonan->kantor->nama === 'PUSAT' ? 'KANTOR PUSAT' : 'CABANG ' . $permohonan->kantor->nama) : '—' }}</p>
                        <p class="text-[11px] text-slate-500 font-mono">NIK: {{ $permohonan->nik_pemohon ?? '—' }}</p>
                    </div>

                    {{-- Jenis Permohonan --}}
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jenis Permohonan</p>
                        <div class="mt-1">
                            @php
                                $jenisVal = $permohonan->jenis_permohonan?->value ?? 'pendaftaran';
                                $jenisBadge = match($jenisVal) {
                                    'pendaftaran' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Pendaftaran Baru'],
                                    'perubahan' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Perubahan User ID'],
                                    'nonaktif' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Non-Aktifkan User'],
                                    default => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'label' => ucfirst($jenisVal)],
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $jenisBadge['bg'] }}">
                                {{ $jenisBadge['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- User ID & Access Level --}}
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">User ID USSI</p>
                        <p class="font-mono font-bold text-slate-800 mt-0.5">{{ $permohonan->user_id_ussi ?? '—' }}</p>
                        @if ($permohonan->access_level)
                            <span class="text-[10px] font-semibold text-slate-500">Level: {{ $permohonan->access_level->value }}</span>
                        @endif
                    </div>
                </div>

                {{-- Status Eksekusi Final --}}
                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500 font-medium">Status Dokumen:</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-extrabold tracking-wide border border-emerald-200">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        EXECUTED / SELESAI
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Section B: Rekam Proses (Audit Trail Digital) ──────────────── --}}
        @if (count($stamps) > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Rekam Proses & Tanda Tangan
                    </h3>
                    <span class="text-[10px] font-semibold text-slate-400">{{ count($stamps) }} Tahapan Selesai</span>
                </div>

                <div class="relative pl-3 border-l-2 border-emerald-200 space-y-4 ml-1.5">
                    @foreach ($stamps as $index => $stamp)
                        @php
                            $role = $stamp['role'] ?? '';
                            $colorMap = match ($role) {
                                'Pemohon'            => ['dot' => 'bg-sky-500',     'border' => 'border-l-sky-500',     'badge' => 'bg-sky-50 text-sky-700',     'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                'Atasan'             => ['dot' => 'bg-emerald-500', 'border' => 'border-l-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'Direktur Utama'     => ['dot' => 'bg-purple-500',  'border' => 'border-l-purple-500',  'badge' => 'bg-purple-50 text-purple-700',  'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                'Administrator USSI' => ['dot' => 'bg-amber-500',   'border' => 'border-l-amber-500',   'badge' => 'bg-amber-50 text-amber-700',   'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                                default              => ['dot' => 'bg-slate-400',   'border' => 'border-l-slate-400',   'badge' => 'bg-slate-100 text-slate-700',  'icon' => 'M5 13l4 4L19 7'],
                            };

                            $aksiLabel = match ($role) {
                                'Pemohon'            => '1. Diajukan oleh Pemohon',
                                'Atasan'             => '2. Disetujui oleh Atasan',
                                'Direktur Utama'     => '3. Disetujui oleh Direksi',
                                'Administrator USSI' => '4. Dieksekusi & Diterbitkan',
                                default              => 'Diproses oleh',
                            };
                        @endphp

                        <div class="relative pl-5 group">
                            {{-- Timeline Node Dot --}}
                            <div class="absolute -left-[21px] top-2.5 w-3.5 h-3.5 rounded-full border-2 border-white {{ $colorMap['dot'] }} ring-2 ring-slate-100 shadow-sm transition-transform group-hover:scale-125"></div>

                            {{-- Timeline Card --}}
                            <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 border-l-4 {{ $colorMap['border'] }} shadow-sm hover:shadow-md transition-all">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $colorMap['badge'] }}">
                                        {{ $aksiLabel }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">Tahap {{ $index + 1 }}</span>
                                </div>

                                <p class="text-[13px] font-bold text-slate-800 leading-tight">{{ $stamp['nama'] }}</p>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $stamp['jabatan'] }}</p>

                                {{-- Timestamp & Hash Container --}}
                                <div class="flex items-center flex-wrap gap-2 mt-2.5 pt-2 border-t border-slate-100 text-[10px]">
                                    <span class="text-slate-500 font-mono flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 6v6l4 2" />
                                        </svg>
                                        {{ $stamp['timestamp'] }}
                                    </span>

                                    <button type="button" @click="copy('{{ $stamp['hash'] }}', 'Signature Hash')"
                                        title="Klik untuk menyalin SHA-256 Signature Hash"
                                        class="font-mono text-slate-600 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 px-1.5 py-0.5 rounded border border-slate-200/80 flex items-center gap-1 transition-colors">
                                        <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                        </svg>
                                        {{ strtoupper(substr($stamp['hash'], 0, 10)) }}…
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── Section C: Trust & Cryptographic Security Info ────────────── --}}
        <div class="bg-gradient-to-br from-slate-50 to-emerald-50/40 border border-emerald-100 rounded-2xl p-4 text-slate-600 space-y-2">
            <div class="flex items-center gap-2 text-emerald-800">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <h4 class="text-xs font-bold uppercase tracking-wide">Jaminan Integritas Digital</h4>
            </div>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Dokumen ini dilindungi secara kriptografis menggunakan Personal Digital Seal & hash SHA-256. Setiap perubahan isi atau pemalsuan tanda tangan akan langsung menggugurkan keabsahan dokumen.
            </p>
        </div>

    </div>

    {{-- ── 3. Panel Footer ──────────────────────────────────────────────── --}}
    <div class="bg-slate-50 border-t border-slate-200/80 p-4 text-center shrink-0">
        <p class="text-[10px] text-slate-400 leading-relaxed font-sans">
            Waktu Verifikasi: <span class="font-medium text-slate-600">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->isoFormat('D MMMM YYYY, HH:mm:ss') }} WIB</span><br>
            <span class="text-emerald-700 font-bold tracking-wider uppercase">eFRUID Security Portal</span> &bull; PT BPR Artha Pamenang
        </p>
    </div>
</div>
