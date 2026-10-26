@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
    <div class="space-y-6" x-data="adminCreateUserForm()">

        {{-- ── Page Header & Navigation Bar ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke Daftar User">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="text-xs font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200 uppercase tracking-wider">
                            Master Pengguna
                        </span>
                        <span class="text-xs text-slate-400">&middot;</span>
                        <span class="text-xs text-slate-500">Registrasi Manual</span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight mt-0.5">
                        Tambah Pengguna Baru
                    </h2>
                </div>
            </div>
        </div>

        {{-- ── 2. Split Form Architecture (8 Col Form + 4 Col Interactive Companion) ── --}}
        <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ══════════════════════════════════════════════════
                 KOLOM KIRI: Form Input Utama (8 Kolom)
            ══════════════════════════════════════════════════ --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- CARD 1: Data Identitas Karyawan --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                        style="animation-delay: 0.05s;">
                        <div
                            class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-xs">
                                    1
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Identitas Karyawan</h3>
                                    <p class="text-xs text-slate-500">Nama lengkap, NIK resmi, dan alamat email</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="label label-required">Nama Lengkap</label>
                                <input name="name" type="text" x-model="name"
                                    class="input @error('name') input-error @enderror" placeholder="Contoh: AHMAD FAUZI"
                                    required>
                                <p class="mt-1 text-xs text-slate-400">Gunakan nama lengkap sesuai dokumen resmi perusahaan
                                </p>
                                @error('name')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- NIK (Auto-format AP + 9 Digit) --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="label label-required mb-0">NIK Karyawan</label>
                                        <span class="text-[11px] font-mono font-semibold"
                                            :class="isNikComplete ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                                            <span x-text="nikDigitCount + '/9 angka'"></span>
                                        </span>
                                    </div>
                                    <div class="relative">
                                        <input name="nik" type="text" x-model="nik"
                                            @input="formatNik($event.target.value)"
                                            class="input font-mono font-bold tracking-wider uppercase pl-3 pr-9 @error('nik') input-error @enderror"
                                            placeholder="AP123456789" maxlength="11" required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <template x-if="isNikComplete">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                            <template x-if="!isNikComplete">
                                                <span class="text-[10px] text-slate-300 font-mono">AP...</span>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-[11px] text-slate-400">
                                        Format otomatis: <strong class="text-slate-600">AP</strong> + 9 digit angka
                                    </p>
                                    @error('nik')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="label label-required">Alamat Email Perusahaan</label>
                                    <input name="email" type="email" x-model="email"
                                        class="input @error('email') input-error @enderror"
                                        placeholder="nama@artha-pamenang.co.id" required>
                                    <p class="mt-1 text-xs text-slate-400">Digunakan untuk notifikasi dan autentikasi</p>
                                    @error('email')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: Penempatan & Jabatan Kerja --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                        style="animation-delay: 0.1s;">
                        <div
                            class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                    2
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Penempatan & Jabatan</h3>
                                    <p class="text-xs text-slate-500">Unit kantor operasional dan jabatan struktural</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Kantor --}}
                                <div>
                                    <label class="label label-required">Kantor Cabang / Pusat</label>
                                    <select name="kantor_id" x-model="kantorId" @change="updateKantorLabel()"
                                        class="input @error('kantor_id') input-error @enderror" required>
                                        <option value="">— Pilih Kantor —</option>
                                        @foreach ($kantor as $k)
                                            <option value="{{ $k->id }}" data-label="{{ $k->nama }}"
                                                @selected(old('kantor_id') == $k->id)>
                                                {{ $k->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kantor_id')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Jabatan --}}
                                <div>
                                    <label class="label label-required">Jabatan Struktural</label>
                                    <select name="jabatan_id" x-model="jabatanId" @change="checkLainnya()"
                                        class="input @error('jabatan_id') input-error @enderror" required>
                                        <option value="">— Pilih Jabatan —</option>
                                        @foreach ($jabatan as $j)
                                            <option value="{{ $j->id }}" data-nama="{{ $j->nama }}"
                                                @selected(old('jabatan_id') == $j->id)>
                                                {{ $j->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_id')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jabatan Custom (Lainnya) --}}
                            <div x-show="isLainnya" x-transition>
                                <label class="label label-required">Nama Jabatan Khusus (Lainnya)</label>
                                <input name="jabatan_custom" type="text" x-model="jabatanCustom"
                                    class="input @error('jabatan_custom') input-error @enderror"
                                    placeholder="Tuliskan nama jabatan secara lengkap" :required="isLainnya">
                                @error('jabatan_custom')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: Hak Akses Role (Single Option) --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                        style="animation-delay: 0.15s;">
                        <div
                            class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                    3
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Hak Akses Role</h3>
                                    <p class="text-xs text-slate-500">Pilih 1 role utama untuk menentukan wewenang pengguna
                                    </p>
                                </div>
                            </div>
                            <span
                                class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                Single Option
                            </span>
                        </div>

                        <div class="p-5 sm:p-6">
                            @php
                                $roleDescriptions = [
                                    'pemohon' => 'Karyawan pemohon FRUID.',
                                    'atasan' => ' Approver permohonan staf.',
                                    'it_staff' => 'Eksekutor konfigurasi user & hak akses sistem.',
                                    'dirut' => 'Direktur Utama yang mengotorisasi permohonan rangkap jabatan.',
                                    'super_admin' => 'Administrator eFRUID.',
                                ];
                            @endphp

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                                @foreach ($roles as $role)
                                    @php
                                        $rVal = $role->value;
                                        $rLabel = $role->label();
                                        $rDesc = $roleDescriptions[$rVal] ?? 'Hak akses wewenang sistem.';
                                    @endphp
                                    <label
                                        class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all focus:outline-none select-none"
                                        :class="selectedRole === '{{ $rVal }}'
                                            ?
                                            'border-brand-600 bg-brand-50/60 shadow-sm shadow-brand-500/10' :
                                            'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-bold"
                                                :class="selectedRole === '{{ $rVal }}' ? 'text-brand-800' :
                                                    'text-slate-800'">
                                                {{ $rLabel }}
                                            </span>
                                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center transition-colors"
                                                :class="selectedRole === '{{ $rVal }}' ?
                                                    'border-brand-600 bg-brand-600' : 'border-slate-300 bg-white'">
                                                <div class="w-1.5 h-1.5 rounded-full bg-white"
                                                    x-show="selectedRole === '{{ $rVal }}'"></div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed"
                                            :class="selectedRole === '{{ $rVal }}' ? 'text-brand-700/80' :
                                                'text-slate-500'">
                                            {{ $rDesc }}
                                        </p>
                                        <input type="radio" name="roles[]" value="{{ $rVal }}"
                                            x-model="selectedRole" class="sr-only">
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="field-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- CARD 4: Kredensial Akun --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-up"
                        style="animation-delay: 0.2s;">
                        <div
                            class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                                    4
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Kredensial Password</h3>
                                    <p class="text-xs text-slate-500">Kata sandi awal untuk akses akun pengguna baru</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            <div>
                                <label class="label label-required">Password Awal</label>
                                <div class="relative">
                                    <input name="password" :type="showPassword ? 'text' : 'password'" x-model="password"
                                        class="input pr-10 @error('password') input-error @enderror"
                                        placeholder="Minimal 8 karakter (kombinasi huruf & angka)" required>
                                    <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                        :aria-label="showPassword ? 'Sembunyikan password' : 'Lihat password'">
                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-slate-400">
                                    Sampaikan password ini kepada pengguna secara aman setelah proses pembuatan akun.
                                </p>
                                @error('password')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ══════════════════════════════════════════════════
                 KOLOM KANAN: Companion Sidebar & Live Preview (4 Kolom)
            ══════════════════════════════════════════════════ --}}
                <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-6">

                    {{-- 1. LIVE ID BADGE PREVIEW CARD --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 animate-fade-up">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                            <span
                                class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live Profile Preview
                            </span>
                            <span
                                class="text-[10px] font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
                                eFRUID ID
                            </span>
                        </div>

                        <div
                            class="flex flex-col items-center text-center p-3 bg-slate-50/80 rounded-xl border border-slate-100">
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-brand-500/20 mb-3">
                                <span x-text="name ? name.substring(0, 1).toUpperCase() : '?'"></span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 truncate max-w-full"
                                x-text="name ? name.toUpperCase() : 'NAMA KARYAWAN'"></h4>
                            <div class="mt-1 flex items-center gap-1.5">
                                <span
                                    class="font-mono text-xs font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200"
                                    x-text="nik ? nik : 'AP_________'"></span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 truncate max-w-full"
                                x-text="email ? email : 'email@artha-pamenang.co.id'"></p>

                            <div
                                class="mt-3 pt-3 border-t border-slate-200/60 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Penempatan:</span>
                                <span class="font-semibold text-slate-700 truncate max-w-[150px]"
                                    x-text="kantorLabel ? kantorLabel : '—'"></span>
                            </div>
                            <div class="mt-1 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Jabatan:</span>
                                <span class="font-semibold text-slate-700 truncate max-w-[150px]"
                                    x-text="isLainnya && jabatanCustom ? jabatanCustom : (jabatanNama ? jabatanNama : '—')"></span>
                            </div>
                            <div class="mt-1 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Role Sistem:</span>
                                <span class="font-bold text-brand-700 uppercase"
                                    x-text="selectedRole ? selectedRole : 'pemohon'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- ACTION HUB CARD --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3 animate-fade-up"
                        style="animation-delay: 0.15s;">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-sm bg-brand-600 text-white hover:bg-brand-700 shadow-md shadow-brand-500/25 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Pengguna</span>
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-semibold text-xs text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                            Batalkan & Kembali
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>

    <script>
        function adminCreateUserForm() {
            return {
                name: '{{ old('name', '') }}',
                nik: '{{ old('nik', '') }}',
                email: '{{ old('email', '') }}',
                kantorId: '{{ old('kantor_id', '') }}',
                kantorLabel: '',
                jabatanId: '{{ old('jabatan_id', '') }}',
                jabatanNama: '',
                jabatanCustom: '{{ old('jabatan_custom', '') }}',
                selectedRole: '{{ old('roles.0', 'pemohon') }}',
                isLainnya: false,
                showPassword: false,
                password: '',
                jabatans: @json($jabatan->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama, 'is_lainnya' => $j->is_lainnya])),

                init() {
                    if (this.nik) {
                        this.formatNik(this.nik);
                    }
                    this.updateKantorLabel();
                    this.checkLainnya();
                },

                formatNik(val) {
                    if (!val) {
                        this.nik = '';
                        return;
                    }
                    let clean = val.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    if (clean.startsWith('AP')) {
                        let digits = clean.substring(2).replace(/\D/g, '').slice(0, 9);
                        this.nik = 'AP' + digits;
                    } else {
                        let digits = clean.replace(/\D/g, '').slice(0, 9);
                        this.nik = digits ? 'AP' + digits : (clean.length > 0 ? 'AP' : '');
                    }
                },

                updateKantorLabel() {
                    const select = document.querySelector('select[name="kantor_id"]');
                    if (select && select.selectedIndex > 0) {
                        this.kantorLabel = select.options[select.selectedIndex].getAttribute('data-label') || select
                            .options[select.selectedIndex].text;
                    } else {
                        this.kantorLabel = '';
                    }
                },

                checkLainnya() {
                    const found = this.jabatans.find(j => String(j.id) === String(this.jabatanId));
                    this.isLainnya = found ? found.is_lainnya : false;
                    this.jabatanNama = found ? found.nama : '';
                },

                get nikDigitCount() {
                    if (!this.nik.startsWith('AP')) return 0;
                    return this.nik.substring(2).length;
                },

                get isNikComplete() {
                    return this.nik.length === 11 && /^AP\d{9}$/.test(this.nik);
                },

                submitForm() {
                    document.getElementById('createUserForm').submit();
                }
            }
        }
    </script>
@endsection
