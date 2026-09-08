@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil & Pengaturan Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- ── 1. Profile Hero Summary Card ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 relative overflow-hidden animate-fade-up">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 relative z-10">
            <div class="flex items-center gap-4">
                {{-- Avatar Monogram Besar --}}
                <div class="relative flex-shrink-0">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-brand-600 via-brand-700 to-slate-900 text-white font-extrabold text-2xl sm:text-3xl flex items-center justify-center shadow-lg shadow-brand-600/20 border-2 border-white">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center shadow-xs" title="Akun Aktif">
                        <span class="w-2 h-2 rounded-full bg-white"></span>
                    </span>
                </div>

                {{-- User Info Detail --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight truncate">
                            {{ $user->name }}
                        </h2>
                        <span class="inline-flex items-center gap-1 font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            NIK: {{ $user->nik }}
                        </span>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-500 mt-1 flex items-center gap-2 flex-wrap font-medium">
                        <span class="inline-flex items-center gap-1 text-slate-700">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->jabatan_label }}
                        </span>
                        <span>&middot;</span>
                        <span class="inline-flex items-center gap-1 text-slate-700">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $user->kantor?->label ?? ($user->kantor?->nama ?? 'BPR Artha Pamenang') }}
                        </span>
                    </p>

                    {{-- Roles & TTD Indicator --}}
                    <div class="flex items-center gap-2 mt-2.5 flex-wrap">
                        @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $role->label }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400">Pegawai</span>
                        @endforelse

                        @if ($user->signature_path)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Tanda Tangan Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Tanda Tangan Belum Ada
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Nav Anchor Buttons --}}
            <div class="flex items-center gap-2 flex-wrap pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 mt-2 sm:mt-0">
                <a href="#profil" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-brand-600 bg-slate-100 hover:bg-brand-50 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Diri
                </a>
                <a href="#password" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-brand-600 bg-slate-100 hover:bg-brand-50 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Password
                </a>
                <a href="#signature" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-brand-600 bg-slate-100 hover:bg-brand-50 rounded-xl transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Tanda Tangan
                </a>
            </div>
        </div>

        {{-- Background Gradient Subtle Accent --}}
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-brand-500/5 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    {{-- ── 2. SECTION: Data Diri Pegawai ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up" id="profil" style="animation-delay: 0.05s;">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Informasi Data Diri</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola identitas akun dan penempatan kantor di sistem eFRUID</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <form method="POST" action="{{ route('profile.update') }}" x-data="profileForm()" @submit="loading = true">
                @csrf
                @method('PUT')

                <script>
                    function profileForm() {
                        return {
                            jabatanId: '{{ old('jabatan_id', $user->jabatan_id) }}',
                            isLainnya: false,
                            loading: false,
                            jabatans: @json($jabatans->map(fn($j) => ['id' => $j->id, 'is_lainnya' => $j->is_lainnya])),
                            init() { this.checkLainnya(); },
                            checkLainnya() {
                                const found = this.jabatans.find(j => String(j.id) === String(this.jabatanId));
                                this.isLainnya = found ? Boolean(found.is_lainnya) : false;
                            }
                        }
                    }
                </script>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Nama Lengkap --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="input w-full text-xs sm:text-sm py-2.5 @error('name') input-error @enderror"
                            placeholder="Nama sesuai identitas resmi" required>
                        @error('name') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIK Karyawan — Read Only --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700">NIK Karyawan</label>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">
                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Terkunci
                            </span>
                        </div>
                        <input type="text" value="{{ $user->nik }}"
                            class="input w-full text-xs sm:text-sm py-2.5 bg-slate-50 text-slate-500 font-mono font-bold cursor-not-allowed border-slate-200"
                            readonly tabindex="-1">
                        <p class="mt-1 text-[11px] text-slate-400">NIK tidak dapat diubah setelah registrasi akun</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}"
                            class="input w-full text-xs sm:text-sm py-2.5 @error('email') input-error @enderror"
                            placeholder="nama@arthapamenang.co.id" required>
                        @error('email') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kantor Cabang --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kantor Cabang / Kas <span class="text-red-500">*</span>
                        </label>
                        <select name="kantor_id" class="input w-full text-xs sm:text-sm py-2.5 @error('kantor_id') input-error @enderror" required>
                            <option value="">— Pilih Kantor Penugasan —</option>
                            @foreach($kantors as $kantor)
                                <option value="{{ $kantor->id }}" @selected(old('kantor_id', $user->kantor_id) == $kantor->id)>
                                    {{ $kantor->label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kantor_id') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jabatan_id" class="input w-full text-xs sm:text-sm py-2.5 @error('jabatan_id') input-error @enderror"
                            x-model="jabatanId" @change="checkLainnya()" required>
                            <option value="">— Pilih Jabatan —</option>
                            @foreach($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}" @selected(old('jabatan_id', $user->jabatan_id) == $jabatan->id)>
                                    {{ $jabatan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan Kustom (Muncul jika Lainnya) --}}
                    <div class="sm:col-span-2" x-show="isLainnya" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        style="display: none;">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Jabatan Spesifik <span class="text-red-500">*</span>
                        </label>
                        <input name="jabatan_custom" type="text" value="{{ old('jabatan_custom', $user->jabatan_custom) }}"
                            class="input w-full text-xs sm:text-sm py-2.5 @error('jabatan_custom') input-error @enderror"
                            placeholder="Tuliskan nama jabatan spesifik Anda" :required="isLainnya">
                        @error('jabatan_custom') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role Sistem (Read Only) --}}
                    <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Hak Akses & Role Sistem</label>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            @forelse($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $role->label }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400">Belum ada role khusus yang diatur</span>
                            @endforelse
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">Hak akses dan wewenang approval diatur oleh Administrator IT</p>
                    </div>

                </div>

                {{-- Action Button --}}
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="btn-primary text-xs sm:text-sm px-6 py-2.5 shadow-xs" :disabled="loading">
                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan Data'">Simpan Perubahan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 3. SECTION: Keamanan Akun & Ganti Password ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up" id="password" style="animation-delay: 0.1s;">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Keamanan & Ganti Password</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui kata sandi secara berkala untuk melindungi akun Anda</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            {{-- Tips Password --}}
            <div class="mb-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3 text-slate-600 text-xs">
                <svg class="w-4 h-4 text-brand-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="space-y-0.5">
                    <span class="font-bold text-slate-800">Ketentuan Keamanan Password:</span>
                    <p>Gunakan minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    {{-- Password Saat Ini --}}
                    <div x-data="{ show: false }">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input name="current_password" :type="show ? 'text' : 'password'"
                                class="input w-full text-xs sm:text-sm py-2.5 pr-10 @error('current_password') input-error @enderror"
                                placeholder="Masukkan kata sandi lama Anda" required>
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors"
                                tabindex="-1" title="Lihat/Sembunyikan Password">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div x-data="{ show: false }">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input name="password" :type="show ? 'text' : 'password'"
                                class="input w-full text-xs sm:text-sm py-2.5 pr-10 @error('password') input-error @enderror"
                                placeholder="Min. 8 karakter (kombinasi huruf & angka)" required>
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors"
                                tabindex="-1" title="Lihat/Sembunyikan Password">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="field-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div x-data="{ show: false }">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input name="password_confirmation" :type="show ? 'text' : 'password'"
                                class="input w-full text-xs sm:text-sm py-2.5 pr-10"
                                placeholder="Ulangi kata sandi baru Anda" required>
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors"
                                tabindex="-1" title="Lihat/Sembunyikan Password">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="btn-primary text-xs sm:text-sm px-6 py-2.5 shadow-xs" :disabled="loading">
                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span x-text="loading ? 'Menyimpan...' : 'Perbarui Password'">Perbarui Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── 4. SECTION: Manajemen Tanda Tangan Digital ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up" id="signature" style="animation-delay: 0.15s;" x-data="signatureManager()">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tanda Tangan Digital</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Digunakan otomatis untuk lembar dokumen FRUID dan sertifikasi approval</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6 space-y-6">

            <script>
                function signatureManager() {
                    return {
                        tab: 'upload',   // 'upload' | 'draw'
                        hasSignature: {{ $user->signature_path ? 'true' : 'false' }},
                        canvas: null,
                        ctx: null,
                        drawing: false,
                        lastX: 0,
                        lastY: 0,
                        isEmpty: true,
                        uploadLoading: false,
                        saveLoading: false,
                        previewUrl: null,

                        switchTab(t) {
                            this.tab = t;
                            if (t === 'draw') {
                                this.$nextTick(() => this.initCanvas());
                            }
                        },

                        initCanvas() {
                            this.canvas = this.$refs.sigCanvas;
                            if (!this.canvas) return;
                            
                            // Setup high-DPI scaling
                            const rect = this.canvas.getBoundingClientRect();
                            this.canvas.width = rect.width * 2;
                            this.canvas.height = 200 * 2;

                            this.ctx = this.canvas.getContext('2d');
                            this.ctx.scale(2, 2);
                            this.ctx.strokeStyle = '#1e3a8a';
                            this.ctx.lineWidth = 3;
                            this.ctx.lineCap = 'round';
                            this.ctx.lineJoin = 'round';
                            this.isEmpty = true;
                        },

                        getPos(e) {
                            const rect = this.canvas.getBoundingClientRect();
                            const src  = e.touches ? e.touches[0] : e;
                            return {
                                x: src.clientX - rect.left,
                                y: src.clientY - rect.top,
                            };
                        },

                        startDraw(e) {
                            e.preventDefault();
                            this.drawing = true;
                            const pos = this.getPos(e);
                            this.lastX = pos.x;
                            this.lastY = pos.y;
                        },

                        draw(e) {
                            e.preventDefault();
                            if (!this.drawing) return;
                            const pos = this.getPos(e);
                            this.ctx.beginPath();
                            this.ctx.moveTo(this.lastX, this.lastY);
                            this.ctx.lineTo(pos.x, pos.y);
                            this.ctx.stroke();
                            this.lastX = pos.x;
                            this.lastY = pos.y;
                            this.isEmpty = false;
                        },

                        stopDraw(e) {
                            if (e) e.preventDefault();
                            this.drawing = false;
                        },

                        clearCanvas() {
                            if (!this.canvas || !this.ctx) return;
                            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                            this.isEmpty = true;
                        },

                        async saveCanvas() {
                            if (this.isEmpty) {
                                alert('Tanda tangan masih kosong. Silakan goreskan tanda tangan Anda.');
                                return;
                            }
                            this.saveLoading = true;
                            const dataUrl = this.canvas.toDataURL('image/png');
                            document.getElementById('signatureData').value = dataUrl;
                            document.getElementById('canvasForm').submit();
                        },

                        previewFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            if (file.type !== 'image/png') {
                                alert('File harus berformat PNG transparan.');
                                e.target.value = '';
                                this.previewUrl = null;
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran berkas maksimal 2MB.');
                                e.target.value = '';
                                this.previewUrl = null;
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (ev) => {
                                this.previewUrl = ev.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            </script>

            {{-- ── Preview Status Tanda Tangan Saat Ini ── --}}
            <div>
                <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Status Tanda Tangan Saat Ini</p>

                @if ($user->signature_path)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200">
                        <div class="flex items-center gap-4">
                            {{-- Preview Image dengan background checkerboard transparansi --}}
                            <div class="h-20 w-36 rounded-xl border border-slate-200 bg-white p-2 flex items-center justify-center flex-shrink-0 shadow-xs relative overflow-hidden"
                                style="background-image: linear-gradient(45deg, #f1f5f9 25%, transparent 25%), linear-gradient(-45deg, #f1f5f9 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #f1f5f9 75%), linear-gradient(-45deg, transparent 75%, #f1f5f9 75%); background-size: 12px 12px; background-position: 0 0, 0 6px, 6px -6px, -6px 0px;">
                                <img src="{{ route('signature.show', $user) }}" alt="Tanda tangan {{ $user->name }}"
                                    class="max-h-full max-w-full object-contain relative z-10">
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Tanda Tangan Tersimpan
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600">
                                    Tanda tangan ini akan dibubuhkan otomatis saat Anda mengajukan, menyetujui, atau mengeksekusi dokumen FRUID.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.signature.delete') }}"
                            onsubmit="return confirm('Hapus tanda tangan digital Anda? Dokumen approval memerlukan tanda tangan.')"
                            class="flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm text-xs shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Tanda Tangan
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-start gap-3.5 text-amber-800">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="text-xs space-y-1">
                            <p class="font-bold text-amber-900 text-sm">Belum Ada Tanda Tangan Digital</p>
                            <p class="leading-relaxed">
                                Tanda tangan digital wajib dimiliki untuk dapat membuat pengajuan, melakukan persetujuan (approval atasan/dirut), atau eksekusi IT. Silakan unggah berkas PNG atau goreskan langsung di bawah.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Tab Switcher Modern ── --}}
            <div class="pt-2 border-t border-slate-100">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        {{ $user->signature_path ? 'Perbarui Tanda Tangan' : 'Pilih Metode Pembuatan' }}
                    </p>

                    <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200/80">
                        <button type="button" @click="switchTab('upload')"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all focus:outline-none"
                            :class="tab === 'upload' ? 'bg-white text-brand-700 shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Unggah Berkas PNG
                        </button>
                        <button type="button" @click="switchTab('draw')"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all focus:outline-none"
                            :class="tab === 'draw' ? 'bg-white text-brand-700 shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Goreskan Langsung (Canvas)
                        </button>
                    </div>
                </div>

                {{-- ── TAB 1: UPLOAD BERKAS PNG ── --}}
                <div x-show="tab === 'upload'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <form method="POST" action="{{ route('profile.signature.upload') }}" enctype="multipart/form-data" @submit="uploadLoading = true">
                        @csrf

                        @error('signature_file')
                            <div class="alert-danger mb-4">
                                <span>{{ $message }}</span>
                            </div>
                        @enderror

                        {{-- Dropzone Area --}}
                        <div class="border-2 border-dashed border-slate-300 hover:border-brand-500 rounded-2xl p-6 sm:p-8 text-center transition-all bg-slate-50/50 hover:bg-brand-50/20 group cursor-pointer relative">
                            <input type="file" name="signature_file" accept=".png,image/png" id="signatureFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="previewFile($event)" required>
                            
                            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-100 text-brand-600 flex items-center justify-center mx-auto mb-3 shadow-xs group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-slate-800 group-hover:text-brand-700 transition-colors">
                                Klik untuk memilih berkas atau seret ke area ini
                            </p>
                            <p class="text-xs text-slate-400 mt-1">
                                Format wajib <strong>PNG transparan</strong> &middot; Ukuran maksimal <strong>2 MB</strong>
                            </p>
                        </div>

                        {{-- Live Preview Uploaded Image --}}
                        <div x-show="previewUrl" x-transition class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4" style="display: none;">
                            <div class="flex items-center gap-3">
                                <div class="h-16 w-32 rounded-lg border border-slate-200 bg-white p-1.5 flex items-center justify-center shadow-xs"
                                    style="background-image: linear-gradient(45deg, #f1f5f9 25%, transparent 25%), linear-gradient(-45deg, #f1f5f9 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #f1f5f9 75%), linear-gradient(-45deg, transparent 75%, #f1f5f9 75%); background-size: 8px 8px;">
                                    <img :src="previewUrl" alt="Preview TTD" class="max-h-full max-w-full object-contain">
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Pratinjau Berkas Terpilih</p>
                                    <p class="text-[11px] text-emerald-600 font-medium">Siap untuk disimpan</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="btn-primary text-xs sm:text-sm px-6 py-2.5 shadow-xs" :disabled="uploadLoading">
                                <svg x-show="uploadLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span x-text="uploadLoading ? 'Mengunggah...' : 'Simpan Tanda Tangan PNG'">Simpan Tanda Tangan PNG</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── TAB 2: GORESKAN LANGSUNG (CANVAS) ── --}}
                <div x-show="tab === 'draw'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">

                    @error('signature_data')
                        <div class="alert-danger mb-4">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Goreskan tanda tangan pada bidang kanvas berikut:
                        </span>
                        <button type="button" @click="clearCanvas()"
                            class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-red-600 bg-slate-100 hover:bg-red-50 px-2.5 py-1 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Hapus & Ulangi
                        </button>
                    </div>

                    {{-- Canvas Frame --}}
                    <div class="border-2 border-slate-300 hover:border-brand-400 rounded-2xl overflow-hidden bg-white shadow-inner cursor-crosshair relative touch-none transition-colors" style="height: 200px;">
                        <canvas x-ref="sigCanvas" class="w-full h-full block"
                            @mousedown="startDraw($event)"
                            @mousemove="draw($event)"
                            @mouseup="stopDraw($event)"
                            @mouseleave="stopDraw($event)"
                            @touchstart="startDraw($event)"
                            @touchmove="draw($event)"
                            @touchend="stopDraw($event)">
                        </canvas>
                        <div x-show="isEmpty" class="absolute inset-0 flex items-center justify-center pointer-events-none text-slate-300 text-xs font-medium">
                            Goreskan tanda tangan di sini menggunakan mouse, layar sentuh, atau stylus
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">Mendukung mouse, touchscreen smartphone/tablet, dan stylus pen</p>

                    {{-- Form Hidden Canvas Submit --}}
                    <form id="canvasForm" method="POST" action="{{ route('profile.signature.canvas') }}">
                        @csrf
                        <input type="hidden" id="signatureData" name="signature_data">
                    </form>

                    <div class="mt-5 flex justify-end">
                        <button type="button" @click="saveCanvas()" class="btn-primary text-xs sm:text-sm px-6 py-2.5 shadow-xs"
                            :disabled="saveLoading || isEmpty">
                            <svg x-show="saveLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span x-text="saveLoading ? 'Menyimpan...' : 'Simpan Goresan Tanda Tangan'">Simpan Goresan Tanda Tangan</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
