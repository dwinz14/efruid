import "./bootstrap";

import Alpine from "alpinejs";
import typewriter from "./components/typewriter";

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

Alpine.data("permohonanForm", (config = {}) => ({
    jenis: config.jenis ?? "pendaftaran",
    tipePerubahan: config.tipePerubahan ?? "",
    formType: config.formType ?? "normal",
    draftUrl: config.draftUrl ?? "/permohonan/draft",

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

    submitDraft() {
        const form = document.getElementById("formStep2");
        form.action = this.draftUrl;
        form.submit();
    },
}));

Alpine.start();
