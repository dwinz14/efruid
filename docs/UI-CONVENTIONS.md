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
