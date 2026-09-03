<div x-data="dokumenModal()" @buka-dokumen-modal.window="buka($event.detail)" @keydown.escape.window="tutup()"
    role="dialog" aria-modal="true" :aria-labelledby="terbuka ? 'modal-judul' : null" x-cloak>
    {{-- Overlay backdrop --}}
    <div x-show="terbuka" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm" @click="tutup()" aria-hidden="true"></div>

    {{-- Panel modal --}}
    <div x-show="terbuka" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4">
        {{-- Container disesuaikan ke max-w-3xl agar proporsional dengan tinggi A4 --}}
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[92vh] flex flex-col overflow-hidden border border-slate-200">

            {{-- Header modal --}}
            <div
                class="flex items-center justify-between gap-4 px-5 py-3.5 border-b border-slate-200 bg-white flex-shrink-0">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <p id="modal-judul" class="font-mono text-xs font-semibold text-slate-500 truncate"
                            x-text="nomorDokumen"></p>
                        {{-- Badge A4 Tag --}}
                        <span
                            class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wider">
                            A4 Document
                        </span>
                    </div>
                    <h2 class="text-sm sm:text-base font-semibold text-slate-800 truncate" x-text="pemohon"></h2>
                </div>

                <div class="flex items-center gap-2.5 flex-shrink-0">
                    {{-- Badge status --}}
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium" :class="statusClass"
                        x-text="status"></span>

                    {{-- Tombol tutup --}}
                    <button type="button" @click="tutup()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400
                               hover:text-slate-600 hover:bg-slate-100 transition-colors"
                        aria-label="Tutup modal">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Area Pratinjau Dokumen (Canvas A4 Viewer) --}}
            <div class="flex-1 relative overflow-y-auto bg-slate-300/50 p-3 sm:p-5 flex justify-center items-start">

                {{-- Indikator loading --}}
                <div x-show="memuat"
                    class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-slate-100/80 backdrop-blur-xs z-10">
                    <svg class="animate-spin w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-500">Memuat dokumen A4...</p>
                </div>

                {{-- Frame Lembaran A4 --}}
                <div
                    class="w-full h-full bg-white shadow-xl rounded-sm border border-slate-200 overflow-hidden relative">
                    <iframe :src="iframeSrc" @load="memuat = false" class="w-full h-full border-none"
                        title="Pratinjau Dokumen FRUID" sandbox="allow-same-origin allow-scripts"></iframe>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-2.5 border-t border-slate-200 flex items-center justify-between flex-shrink-0 bg-white">
                <p class="text-xs text-slate-400 italic">Dokumen ini hanya dapat dilihat, tidak dapat diubah dari
                    halaman ini.</p>
                <button type="button" @click="tutup()"
                    class="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-md transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
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

            buka({
                previewUrl,
                nomorDokumen,
                pemohon,
                status,
                statusClass
            }) {
                this.nomorDokumen = nomorDokumen;
                this.pemohon = pemohon;
                this.status = status;
                this.statusClass = statusClass;
                this.memuat = true;
                this.iframeSrc = previewUrl;
                this.terbuka = true;

                document.body.style.overflow = 'hidden';
            },

            tutup() {
                this.terbuka = false;
                this.iframeSrc = '';
                this.memuat = false;
                this.nomorDokumen = '';
                this.pemohon = '';
                this.status = '';
                this.statusClass = '';

                document.body.style.overflow = '';
            },
        };
    }
</script>
