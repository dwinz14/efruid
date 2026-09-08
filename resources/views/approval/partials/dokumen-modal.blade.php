<div x-data="dokumenModal()" @buka-dokumen-modal.window="buka($event.detail)" @keydown.escape.window="tutup()" x-cloak>

    {{-- Teleport modal langsung ke <body> agar berada di luar semua Stacking Context parent --}}
    <template x-teleport="body">
        <div x-show="terbuka"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-5 md:p-6 overflow-hidden select-none"
            role="dialog" aria-modal="true" :aria-labelledby="terbuka ? 'modal-judul' : null">

            {{-- 1. Full-Screen Backdrop Blur (Menutupi 100% Layar Termasuk Sidebar & Topbar) --}}
            <div x-show="terbuka" x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-0 bg-slate-950/70 backdrop-blur-md"
                @click="tutup()" aria-hidden="true"></div>

            {{-- 2. Modal Dialog Panel (Tampil Proporsional & Mengambang di Tengah) --}}
            <div x-show="terbuka" x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[92vh] max-h-[960px] flex flex-col overflow-hidden border border-slate-200/80 ring-1 ring-slate-900/10">

                {{-- Header Modal --}}
                <div
                    class="flex items-center justify-between gap-4 px-5 sm:px-6 py-4 border-b border-slate-100 bg-white flex-shrink-0">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div
                            class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center flex-shrink-0 text-brand-600 shadow-xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span id="modal-judul"
                                    class="font-mono text-xs font-bold text-slate-700 tracking-wide truncate bg-slate-100 px-2 py-0.5 rounded border border-slate-200/80"
                                    x-text="nomorDokumen"></span>
                                <span
                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 uppercase tracking-wider hidden sm:inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                                    Dokumen FRUID
                                </span>
                            </div>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 truncate flex items-center gap-2">
                                <span class="text-slate-500 font-normal text-xs sm:text-sm">Pemohon:</span>
                                <span x-text="pemohon" class="text-slate-800"></span>
                            </h2>
                        </div>
                    </div>

                    {{-- Kontrol Aksi Header --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        {{-- Status Badge --}}
                        <span class="text-xs px-3 py-1 rounded-full font-semibold border shadow-xs"
                            :class="statusClass" x-text="status"></span>

                        {{-- Tombol Tutup (X) --}}
                        <button type="button" @click="tutup()"
                            class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300"
                            aria-label="Tutup modal">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Area Pratinjau Dokumen (Canvas A4 Viewer) --}}
                <div
                    class="flex-1 relative overflow-hidden bg-slate-100/90 p-3 sm:p-5 flex justify-center items-stretch select-text">

                    {{-- Loading Indicator / Skeleton --}}
                    <div x-show="memuat" x-transition:leave="transition ease-out duration-300"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-slate-50/95 backdrop-blur-xs z-20">
                        <div
                            class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shadow-sm animate-pulse">
                            <svg class="animate-spin w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Memuat Dokumen FRUID...</p>
                            <p class="text-xs text-slate-400 mt-0.5">Menyiapkan pratinjau lembar A4</p>
                        </div>
                    </div>

                    {{-- Lembaran Dokumen --}}
                    <div
                        class="w-full h-full bg-white shadow-xl rounded-xl border border-slate-200/80 overflow-hidden relative flex flex-col">
                        <iframe :src="iframeSrc" @load="memuat = false"
                            class="w-full h-full border-none bg-white flex-1" title="Pratinjau Dokumen FRUID"
                            sandbox="allow-same-origin allow-scripts allow-popups">
                        </iframe>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div
                    class="px-5 sm:px-6 py-3 border-t border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden sm:inline">Dokumen ini bersifat <span
                                class="font-medium text-slate-700">read-only</span> untuk keperluan verifikasi dan arsip
                            persetujuan.</span>
                        <span class="sm:hidden">Mode Read-Only</span>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button type="button" @click="tutup()"
                            class="px-4 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>
</div>

<script>
    function dokumenModal() {
        return {
            terbuka: false,
            memuat: false,
            iframeSrc: '',
            nomorDokumen: '',
            pemohon: '',
            status: '',
            statusClass: '',

            buka(detail) {
                this.nomorDokumen = detail.nomorDokumen || 'Dokumen FRUID';
                this.pemohon = detail.pemohon || '—';
                this.status = detail.status || '';
                this.statusClass = detail.statusClass || 'badge-pending';
                this.memuat = true;
                this.iframeSrc = detail.previewUrl || '';
                this.terbuka = true;

                document.body.classList.add('overflow-hidden');
            },

            tutup() {
                this.terbuka = false;
                setTimeout(() => {
                    if (!this.terbuka) {
                        this.iframeSrc = '';
                        this.memuat = false;
                        this.nomorDokumen = '';
                        this.pemohon = '';
                        this.status = '';
                        this.statusClass = '';
                    }
                }, 200);

                document.body.classList.remove('overflow-hidden');
            },
        };
    }
</script>
