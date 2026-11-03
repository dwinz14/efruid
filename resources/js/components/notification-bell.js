// Bel notifikasi di topbar. URL endpoint dibaca dari atribut data-* pada elemen root.
const POLL_MS = 30000;

const ICONS = {
    permohonan_submitted:
        "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2",
    permohonan_ready_it:
        "M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z",
    permohonan_need_dirut:
        "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z",
    permohonan_rejected:
        "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z",
    permohonan_executed: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
};

const COLORS = {
    permohonan_submitted: "text-amber-700 bg-amber-50",
    permohonan_need_dirut: "text-purple-700 bg-purple-50",
    permohonan_ready_it: "text-brand-700 bg-brand-50",
    permohonan_rejected: "text-red-700 bg-red-50",
    permohonan_executed: "text-emerald-700 bg-emerald-50",
};

export default () => ({
    open: false,
    count: 0,
    notifications: [],
    loading: false,
    error: false,
    urls: {},
    timer: null,
    onVisible: null,

    init() {
        this.urls = {
            count: this.$el.dataset.countUrl,
            index: this.$el.dataset.indexUrl,
            read: this.$el.dataset.readUrl,
            readAll: this.$el.dataset.readAllUrl,
        };

        this.fetchCount();

        // Polling hanya saat tab terlihat; segera segarkan saat tab kembali aktif
        this.timer = setInterval(() => {
            if (!document.hidden) this.fetchCount();
        }, POLL_MS);

        this.onVisible = () => {
            if (!document.hidden) this.fetchCount();
        };
        document.addEventListener("visibilitychange", this.onVisible);
    },

    destroy() {
        clearInterval(this.timer);
        document.removeEventListener("visibilitychange", this.onVisible);
    },

    async request(url, { method = "GET", body = null } = {}) {
        const headers = {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        };

        if (method !== "GET") {
            headers["Content-Type"] = "application/json";
            headers["X-CSRF-TOKEN"] = document.querySelector(
                "meta[name=csrf-token]",
            ).content;
        }

        const res = await fetch(url, {
            method,
            headers,
            body: body ? JSON.stringify(body) : null,
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        return res.json().catch(() => ({}));
    },

    async fetchCount() {
        try {
            const data = await this.request(this.urls.count);
            this.count = data.count ?? 0;
        } catch (e) {
            // Gagal saat polling: abaikan, coba lagi di siklus berikutnya
        }
    },

    async toggle() {
        this.open = !this.open;
        if (this.open) await this.load();
    },

    close(returnFocus = false) {
        this.open = false;
        if (returnFocus) this.$refs.trigger?.focus();
    },

    async load() {
        this.loading = true;
        this.error = false;

        try {
            const data = await this.request(this.urls.index);
            this.notifications = data.notifications ?? [];
        } catch (e) {
            this.error = true;
        } finally {
            this.loading = false;
        }
    },

    async markRead(notif) {
        try {
            await this.request(this.urls.read, {
                method: "POST",
                body: { id: notif.id },
            });

            if (!notif.read) {
                notif.read = true;
                if (this.count > 0) this.count--;
            }
        } catch (e) {
            // Tetap lanjut ke halaman tujuan walau gagal menandai dibaca
        }

        if (notif.url) window.location.href = notif.url;
    },

    async markAllRead() {
        try {
            await this.request(this.urls.readAll, { method: "POST" });
            this.notifications.forEach((n) => (n.read = true));
            this.count = 0;
        } catch (e) {
            window.showToast?.("Gagal menandai notifikasi dibaca.", "error");
        }
    },

    iconForType(type) {
        return ICONS[type] ?? ICONS.permohonan_submitted;
    },

    colorForType(type) {
        return COLORS[type] ?? "text-slate-600 bg-slate-100";
    },
});