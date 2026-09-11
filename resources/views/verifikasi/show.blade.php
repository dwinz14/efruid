<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#047857">
    <meta name="description" content="Verifikasi Dokumen Resmi eFRUID — {{ $permohonan->nomor_dokumen }}">
    <title>Verifikasi Dokumen — {{ $permohonan->nomor_dokumen }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mencegah highlight biru pada mobile saat disentuh */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Shimmer animation */
        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            animation: shimmer 1.8s infinite;
        }

        /* Anti Print */
        @media print {
            body {
                display: none !important;
            }
        }
    </style>

    <script>
        // Definisikan controller sebelum Alpine / DOMContentLoaded dievaluasi
        window.verificationApp = function() {
            return {
                showLoader: true,
                docLoaded: false,
                sheetOpen: false,
                activeTab: 'doc', // 'doc' | 'panel' (untuk tampilan mobile switcher)
                zoomLevel: 1,
                isFullscreen: false,
                isHidden: false,

                DOC_W: 780,
                DOC_H: 1150,

                startY: 0,
                currentY: 0,

                initApp() {
                    // Auto dismiss splash screen
                    setTimeout(() => {
                        this.dismissLoader();
                    }, 400);

                    // Timeout fallback untuk iframe jika event onload lambat
                    setTimeout(() => {
                        this.docLoaded = true;
                    }, 4000);

                    this.$nextTick(() => {
                        this.scaleDocument();

                        // ResizeObserver — BARU
                        if (window.ResizeObserver) {
                            const ro = new ResizeObserver(() => {
                                this.scaleDocument();
                            });
                            ro.observe(this.$refs.docContainer);
                            this._resizeObserver = ro;
                        }
                    });
                },

                dismissLoader() {
                    this.showLoader = false;
                    const splashEl = document.getElementById('splash-loader');
                    if (splashEl) {
                        splashEl.style.opacity = '0';
                        splashEl.style.pointerEvents = 'none';
                        setTimeout(() => {
                            if (splashEl.parentNode) {
                                splashEl.parentNode.removeChild(splashEl);
                            }
                        }, 500);
                    }
                    this.scaleDocument();
                },

                scaleDocument() {
                    const container = this.$refs.docContainer;
                    const wrapper = this.$refs.docWrapper;
                    if (!container || !wrapper) return;

                    const isDesktop = window.innerWidth >= 1024;
                    // Gunakan offsetWidth agar tidak terpengaruh padding inherited
                    const containerWidth = container.offsetWidth;

                    if (isDesktop) {
                        // Desktop: set explicit pixel width & height, reset zoom/transform
                        const targetWidth = Math.min(containerWidth - 64, this.DOC_W) * this.zoomLevel;
                        wrapper.style.zoom = '1';
                        wrapper.style.width = targetWidth + 'px';
                        wrapper.style.height = (this.DOC_H * (targetWidth / this.DOC_W)) + 'px';
                        wrapper.style.transform = 'none';
                        wrapper.style.transformOrigin = '';
                    } else {
                        const padding = 24;
                        const availableW = Math.max(containerWidth - padding, 280);
                        const ratio = Math.min(
                            (availableW / this.DOC_W) * this.zoomLevel,
                            1 // max 1x
                        );
                        const scaledH = this.DOC_H * ratio;

                        // Wrapper tetap intrinsik 780×1150
                        wrapper.style.width = this.DOC_W + 'px';
                        wrapper.style.height = this.DOC_H + 'px';

                        // Scale via transform — reliable di semua browser
                        wrapper.style.transform = `scale(${ratio})`;
                        wrapper.style.transformOrigin = 'top left';
                        wrapper.style.zoom = ''; // reset zoom
                        wrapper.style.transition = 'transform 0.15s ease';

                        // Height compensation — KRUSIAL!
                        // scaler memberi ruang vertikal yang tepat
                        const scaler = this.$refs.docScaler;
                        if (scaler) {
                            scaler.style.width = availableW + 'px';
                            scaler.style.height = scaledH + 'px';
                        }
                    }
                },

                zoomIn() {
                    if (this.zoomLevel < 1.6) {
                        this.zoomLevel = Math.round((this.zoomLevel + 0.15) * 100) / 100;
                        this.scaleDocument();
                    }
                },

                zoomOut() {
                    if (this.zoomLevel > 0.6) {
                        this.zoomLevel = Math.round((this.zoomLevel - 0.15) * 100) / 100;
                        this.scaleDocument();
                    }
                },

                zoomReset() {
                    this.zoomLevel = 1;
                    this.scaleDocument();
                },

                toggleFullscreen() {
                    this.isFullscreen = !this.isFullscreen;
                    this.$nextTick(() => this.scaleDocument());
                },

                touchStart(e) {
                    this.startY = e.touches[0].clientY;
                },
                touchMove(e) {
                    this.currentY = e.touches[0].clientY;
                },
                touchEnd() {
                    if (this.currentY - this.startY > 70) {
                        this.sheetOpen = false;
                    }
                    this.startY = 0;
                    this.currentY = 0;
                },

                handleKeydown(e) {
                    const ctrl = e.ctrlKey || e.metaKey;
                    const shifted = e.shiftKey;
                    const k = e.key.toLowerCase();

                    const blocked =
                        (ctrl && !shifted && ['c', 'a', 's', 'p', 'u'].includes(k)) ||
                        (ctrl && shifted && ['i', 'j', 'c'].includes(k)) ||
                        e.key === 'F12' || e.key === 'PrintScreen';

                    if (blocked) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                },

                handleVisibility() {
                    this.isHidden = document.hidden;
                }
            };
        };

        // Fail-safe auto dismiss jika script Alpine terlambat boot
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const splashEl = document.getElementById('splash-loader');
                if (splashEl && splashEl.style.opacity !== '0') {
                    splashEl.style.opacity = '0';
                    splashEl.style.pointerEvents = 'none';
                    setTimeout(() => {
                        if (splashEl.parentNode) {
                            splashEl.parentNode.removeChild(splashEl);
                        }
                    }, 500);
                }
            }, 800);
        });
    </script>
</head>

<body
    class="h-full bg-slate-900 text-slate-800 antialiased overflow-hidden selection:bg-emerald-200 selection:text-emerald-900"
    x-data="verificationApp()" x-init="initApp()" @contextmenu.prevent="true" @dragstart.prevent="true"
    @keydown.window="handleKeydown($event)" @visibilitychange.window="handleVisibility()"
    :style="isHidden ? 'filter: blur(25px)' : ''">

    {{-- ── 1. App Loader (Fail-Safe Splash Screen) ──────────────────────── --}}
    <div id="splash-loader"
        class="fixed inset-0 z-[9999] bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 flex flex-col items-center justify-center p-6 text-center transition-all duration-500 ease-out">

        {{-- Glowing Emblem --}}
        <div class="relative flex items-center justify-center mb-6">
            <div class="absolute w-24 h-24 bg-emerald-500/20 rounded-full blur-xl animate-pulse"></div>
            <div
                class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center shadow-2xl relative">
                <svg class="w-8 h-8 text-emerald-400 animate-bounce" fill="none" stroke="currentColor"
                    stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
        </div>

        <div class="space-y-1.5">
            <h2 class="text-white text-base md:text-lg font-extrabold tracking-wide uppercase">Memverifikasi Dokumen
            </h2>
            <p class="text-emerald-200/80 text-xs font-medium tracking-wide">eFRUID &bull; PT BPR Artha Pamenang</p>
        </div>

        {{-- Bouncing Dots --}}
        <div class="flex items-center space-x-1.5 mt-5">
            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
        </div>
    </div>

    {{-- ── 2. Top Security & Header Bar ─────────────────────────────────── --}}
    <header
        class="fixed top-0 inset-x-0 z-40 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-white shadow-md">
        {{-- Top Disclaimer Stripe --}}
        <div
            class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 text-white text-[11px] font-bold py-1 px-4 text-center tracking-wide flex items-center justify-center gap-1.5 shadow-inner">
            <svg class="w-3.5 h-3.5 text-emerald-100 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Halaman Verifikasi Resmi Digital eFRUID &mdash; PT BPR Artha Pamenang</span>
        </div>

        {{-- Main Navigation Bar --}}
        <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center justify-between gap-3">
            {{-- Left: Logo & Doc Number --}}
            <div class="flex items-center gap-3 min-w-0">
                <div
                    class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span
                            class="text-xs font-extrabold tracking-wider text-emerald-400 uppercase">Terverifikasi</span>
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    </div>
                    <p class="text-xs font-mono text-slate-300 truncate font-semibold">{{ $permohonan->nomor_dokumen }}
                    </p>
                </div>
            </div>

            {{-- Right: Zoom & Action Toolbar (Desktop) --}}
            <div class="hidden lg:flex items-center gap-2">
                {{-- Zoom Controls --}}
                <div
                    class="flex items-center bg-slate-800 border border-slate-700 rounded-xl p-0.5 text-xs text-slate-300">
                    <button type="button" @click="zoomOut()" title="Perkecil (-)"
                        class="p-1.5 hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                        </svg>
                    </button>
                    <span class="px-2.5 font-mono font-bold text-slate-200"
                        x-text="Math.round(zoomLevel * 100) + '%'">100%</span>
                    <button type="button" @click="zoomIn()" title="Perbesar (+)"
                        class="p-1.5 hover:bg-slate-700 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <button type="button" @click="zoomReset()" title="Reset Ukuran"
                        class="px-2 py-1 text-[10px] font-bold text-slate-400 hover:text-white hover:bg-slate-700 rounded-md transition-colors border-l border-slate-700">
                        Reset
                    </button>
                </div>

                {{-- Fullscreen Toggle --}}
                <button type="button" @click="toggleFullscreen()" title="Layar Penuh"
                    class="p-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-slate-300 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    {{-- ── 3. Main Split Layout ─────────────────────────────────────────── --}}
    <main class="h-full flex flex-col lg:flex-row w-full pt-[78px] bg-slate-900">

        <section
            class="flex-1 min-w-0 relative bg-slate-950 flex flex-col items-center overflow-x-hidden overflow-y-auto custom-scrollbar p-3 sm:p-6"
            x-ref="docContainer">

            {{-- Scaler Container Wrapper for Mobile and Desktop --}}
            <div x-ref="docScaler" class="relative flex-shrink-0">
                <div x-ref="docWrapper"
                    class="bg-white rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] relative origin-top-left"
                    style="transition: transform 0.15s ease, width 0.15s ease, height 0.15s ease;">

                    {{-- Shimmer Loading State --}}
                    <div x-show="!docLoaded"
                        class="absolute inset-0 z-20 bg-white flex flex-col items-center justify-center p-8">
                        <div class="w-full max-w-md space-y-4">
                            <div class="h-28 bg-slate-100 rounded-xl relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-slate-200 to-transparent animate-shimmer">
                                </div>
                            </div>
                            <div class="h-5 bg-slate-100 rounded w-full relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-slate-200 to-transparent animate-shimmer">
                                </div>
                            </div>
                            <div class="h-4 bg-slate-100 rounded w-4/5 relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-slate-200 to-transparent animate-shimmer">
                                </div>
                            </div>
                            <div class="h-4 bg-slate-100 rounded w-2/3 relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-slate-200 to-transparent animate-shimmer">
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 font-semibold mt-6 tracking-wide animate-pulse">Memuat lembar
                            dokumen digital...</p>
                    </div>
                    <iframe x-ref="docFrame" src="{{ route('verifikasi.document', ['token' => $token]) }}"
                        sandbox="allow-same-origin allow-scripts"
                        title="Dokumen FRUID {{ $permohonan->nomor_dokumen }}" scrolling="no"
                        @load="docLoaded = true" class="w-full h-full border-0 absolute inset-0 bg-white"
                        style="display: block;"></iframe>
                </div>
            </div>
        </section>

        {{-- Right Area: Desktop Sidebar Panel --}}
        <aside
            class="hidden lg:flex flex-col w-[410px] bg-white border-l border-slate-800 shadow-2xl z-30 h-full overflow-hidden relative">
            @include('verifikasi._panel', ['permohonan' => $permohonan, 'stamps' => $stamps])
        </aside>

    </main>

    {{-- ── 4. Mobile Bottom Action Bar (Floating Trigger) ──────────────── --}}
    <div class="lg:hidden fixed bottom-4 inset-x-4 z-40">
        <button type="button" @click="sheetOpen = true"
            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl py-3.5 px-5 shadow-2xl flex items-center justify-between active:scale-[0.98] transition-transform border border-emerald-400/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-xs font-extrabold uppercase tracking-wide leading-tight">Detail & Riwayat Verifikasi
                    </p>
                    <p class="text-[10px] text-emerald-100 font-medium">Klik untuk melihat tanda tangan digital</p>
                </div>
            </div>
            <svg class="w-5 h-5 opacity-90 animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    {{-- ── 5. Mobile Bottom Sheet Modal ─────────────────────────────────── --}}
    <div class="lg:hidden" x-cloak>
        {{-- Overlay Backdrop --}}
        <div x-show="sheetOpen" x-transition.opacity.duration.300ms @click="sheetOpen = false"
            class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm"></div>

        {{-- Sheet Container --}}
        <div x-show="sheetOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="fixed inset-x-0 bottom-0 z-50 bg-white rounded-t-3xl shadow-2xl max-h-[88vh] flex flex-col overflow-hidden"
            @touchstart="touchStart" @touchmove="touchMove" @touchend="touchEnd">

            {{-- Handle Bar --}}
            <div class="w-full py-3.5 flex justify-center shrink-0 cursor-grab active:cursor-grabbing bg-slate-50 border-b border-slate-100"
                @click="sheetOpen = false">
                <div class="w-12 h-1.5 bg-slate-300 rounded-full"></div>
            </div>

            {{-- Panel Content Inside Sheet --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @include('verifikasi._panel', ['permohonan' => $permohonan, 'stamps' => $stamps])
            </div>
        </div>
    </div>

</body>

</html>
