import "./bootstrap";

import Alpine from "alpinejs";
import typewriter from "./components/typewriter";
import '@fontsource-variable/inter';
import notificationBell from "./components/notification-bell";

window.Alpine = Alpine;

// Global confirm dialog helper
window.confirmAction = (message, callback) => {
    if (window.confirm(message)) {
        callback();
    }
};

// Global toast helper (dispatch event)
window.showToast = (message, type = "success") => {
    window.dispatchEvent(
        new CustomEvent("show-toast", {
            detail: { message, type },
        }),
    );
};

Alpine.data("typewriter", typewriter);
Alpine.data("notificationBell", notificationBell);

Alpine.data("permohonanForm", (config = {}) => ({
    jenis: config.jenis ?? "pendaftaran",
    tipePerubahan: config.tipePerubahan ?? "",
    formType: config.formType ?? "normal",
    draftUrl: config.draftUrl ?? "/permohonan/draft",

    // User ID USSI: terisi otomatis dari NIK, tetapi boleh diubah pengguna
    userId: config.userId ?? "",
    userIdDefault: config.userIdDefault ?? "",

    // Tanggal sementara: batas minimal "sampai" mengikuti "mulai"
    tglMulai: config.tglMulai ?? "",

    // null | 'preview' | 'draft'
    submitting: null,

    init() {
        // Kembali via tombol Back browser (bfcache): jangan biarkan tombol terkunci
        window.addEventListener("pageshow", (e) => {
            if (e.persisted) this.submitting = null;
        });
    },

    get isPerubahan() {
        return this.jenis === "perubahan";
    },
    get isNonaktif() {
        return this.jenis === "nonaktif";
    },
    get isPermanen() {
        return this.jenis === "perubahan" && this.tipePerubahan === "permanen";
    },
    get isSementara() {
        return this.jenis === "perubahan" && this.tipePerubahan === "sementara";
    },
    get busy() {
        return this.submitting !== null;
    },

    get userIdChanged() {
        return this.userId !== this.userIdDefault;
    },
    resetUserId() {
        this.userId = this.userIdDefault;
    },

    get minTglSelesai() {
        const pad = (n) => String(n).padStart(2, "0");
        const base = this.tglMulai
            ? new Date(`${this.tglMulai}T00:00:00`)
            : new Date();
        base.setDate(base.getDate() + 1);

        return `${base.getFullYear()}-${pad(base.getMonth() + 1)}-${pad(base.getDate())}`;
    },

    submitDraft() {
        // Draft boleh disimpan tanpa atasan; aturan validasinya dijaga server (validateStep2).
        this.submitting = "draft";
        const form = this.$root;
        form.action = this.draftUrl;
        form.submit();
    },
}));

Alpine.start();
