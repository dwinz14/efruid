# Konvensi UI eFRUID

- **Font:** Inter (di-host lewat npm). Teks isi minimal `text-sm`; `text-xs` hanya untuk keterangan sekunder. Hindari `text-[10px]/[11px]`.
- **Radius:** kontrol (input, tombol) `rounded-lg`; kartu `rounded-card` (= `rounded-xl`). Jangan pakai `rounded-2xl` untuk kartu baru.
- **Kartu/tombol/input:** pakai kelas komponen `.card`, `.btn-*`, `.input`, `.label` dari `app.css`, bukan menulis ulang utilitas.
- **Bayangan:** hanya `shadow-card` atau `shadow-sm`. Tidak ada `shadow-xs` (tidak ada di Tailwind v3).
- **Warna semantik:** sukses = emerald, peringatan = amber, bahaya = red, info/aksi utama = brand. Jangan pakai `green`/`yellow`.
- **Status permohonan:** selalu `<x-status-badge :status="$p->status" />`. Warna didefinisikan satu kali di `StatusPermohonan::badgeClass()` + `app.css`. Jangan membuat badge status manual.
- **Header halaman:** judul lewat `@section('page-title')`; deskripsi, tombol kembali, dan aksi utama lewat `<x-page-header>`. Jangan menulis ulang judul di konten.
- **Lebar konten:** `@section('width', 'sm|md|lg')`; jangan menulis `max-w-* mx-auto` sendiri di halaman.
- **Pesan flash:** lewat `with('success'|'info'|'warning'|'error')` atau `withErrors(['error' => ...])`; ditampilkan oleh layout.
- **Kelas Tailwind dari PHP:** simpan di `app/Enums` (sudah masuk `content`) atau tambahkan path-nya ke `tailwind.config.js`.
- **Form:** bungkus tiap kolom dengan `<x-field name label [required] [optional] [hint]>`; kontrolnya wajib memakai `id` = `name`, `aria-describedby="{name}-hint {name}-error"`, dan `aria-invalid="true"` saat error. Grup pilihan = `<fieldset>` + `<legend>` + `<x-choice-card>`.
- **Kartu bagian form:** `<x-form-section title description>`; alert inline: `<x-alert type="success|info|warning|danger" [title] [dismissible]>`; spinner: `<x-spinner />`.
- **Wizard:** stepper = `permohonan.partials.stepper`; bilah aksi = `<x-wizard-actions>` (slot `back` + aksi); tombol aksi `min-h-11`.
