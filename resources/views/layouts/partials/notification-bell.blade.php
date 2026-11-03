{{-- Bel notifikasi. Logika di resources/js/components/notification-bell.js --}}
<div x-data="notificationBell" class="relative flex-shrink-0" data-count-url="{{ route('notifications.count') }}"
    data-index-url="{{ route('notifications.index') }}" data-read-url="{{ route('notifications.read') }}"
    data-read-all-url="{{ route('notifications.readAll') }}" @keydown.escape.window="open && close(true)">

    {{-- Tombol bel --}}
    <button type="button" x-ref="trigger" @click="toggle()" aria-haspopup="true" aria-controls="notif-panel"
        :aria-expanded="open.toString()"
        class="relative rounded-full p-2.5 text-slate-600 transition-colors hover:bg-brand-50 hover:text-brand-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
        <span class="sr-only"
            x-text="count > 0 ? 'Notifikasi, ' + count + ' belum dibaca' : 'Notifikasi'">Notifikasi</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <template x-if="count > 0">
            <span class="absolute right-1.5 top-1.5 flex h-3 w-3" aria-hidden="true">
                <span
                    class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75 motion-safe:animate-ping"></span>
                <span class="relative inline-flex h-3 w-3 rounded-full border-2 border-white bg-red-500"></span>
            </span>
        </template>
    </button>

    {{-- Panel: penuh lebar (dengan margin) di layar kecil, dropdown 384px di layar ≥ sm --}}
    <div id="notif-panel" x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1" @click.outside="open = false" role="region"
        aria-label="Daftar notifikasi"
        class="fixed inset-x-4 top-16 z-50 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg sm:absolute sm:inset-x-auto sm:right-0 sm:top-full sm:mt-2 sm:w-96">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-4 py-3">
            <p class="text-sm font-semibold text-slate-800">
                Notifikasi
                <span x-show="count > 0"
                    class="ml-1 rounded-full bg-brand-100 px-2 py-0.5 text-xs font-semibold text-brand-700"
                    x-text="count"></span>
            </p>
            <button type="button" x-show="count > 0" @click="markAllRead()"
                class="rounded text-xs font-medium text-brand-700 hover:text-brand-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                Tandai semua dibaca
            </button>
        </div>

        {{-- Memuat --}}
        <div x-show="loading" role="status" class="flex flex-col items-center justify-center px-4 py-8">
            <svg class="mb-3 h-7 w-7 text-brand-500 motion-safe:animate-spin" fill="none" viewBox="0 0 24 24"
                aria-hidden="true">
                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span class="text-xs text-slate-500">Memuat notifikasi…</span>
        </div>

        {{-- Gagal memuat --}}
        <div x-show="!loading && error" role="alert" class="px-4 py-8 text-center">
            <p class="text-sm font-medium text-slate-700">Notifikasi gagal dimuat.</p>
            <button type="button" @click="load()"
                class="mt-2 rounded text-sm font-medium text-brand-700 hover:text-brand-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                Coba lagi
            </button>
        </div>

        {{-- Daftar --}}
        <div x-show="!loading && !error" class="custom-scrollbar max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="flex flex-col items-center justify-center px-4 py-10 text-center">
                    <p class="text-sm font-medium text-slate-700">Belum ada notifikasi</p>
                    <p class="mt-1 text-xs text-slate-500">Semua pembaruan akan muncul di sini.</p>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <a :href="notif.url" @click.prevent="markRead(notif)"
                    class="relative flex items-start gap-3 border-b border-slate-100 px-4 py-3 transition-colors last:border-0 hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand-500"
                    :class="notif.read ? 'bg-white' : 'bg-brand-50/40'">

                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                        :class="colorForType(notif.data.type)" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="iconForType(notif.data.type)" />
                        </svg>
                    </span>

                    <span class="min-w-0 flex-1 pr-4">
                        <span class="block text-sm leading-snug text-slate-800"
                            :class="notif.read ? 'font-normal' : 'font-medium'" x-text="notif.data.pesan"></span>
                        <span class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                            <span class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-xs text-slate-600"
                                x-text="notif.data.nomor_dokumen"></span>
                            <span class="text-xs text-slate-500" x-text="notif.created_at"></span>
                        </span>
                    </span>

                    <span x-show="!notif.read"
                        class="absolute right-4 top-1/2 h-2 w-2 -translate-y-1/2 rounded-full bg-brand-500">
                        <span class="sr-only">Belum dibaca</span>
                    </span>
                </a>
            </template>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-4 py-2.5 text-center">
            <span class="text-xs text-slate-500">Menampilkan 10 notifikasi terbaru</span>
        </div>
    </div>
</div>
