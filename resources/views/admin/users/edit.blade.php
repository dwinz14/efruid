@extends('layouts.app')

@section('title', 'Edit User — ' . $user->name)
@section('page-title', 'Edit User')

@section('content')
    <div class="space-y-6" x-data="adminEditUserForm()">

        {{-- ── 1. Page Header & Navigation Bar ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}"
                    class="p-2.5 bg-white rounded-xl border border-slate-200/80 shadow-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500/20 active:scale-95 flex-shrink-0"
                    title="Kembali ke Detail User">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span
                            class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            {{ $user->nik }}
                        </span>
                        <span class="text-xs text-slate-400">&middot;</span>
                        <span class="text-xs font-semibold {{ $user->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $user->is_active ? 'Akun Aktif' : 'Akun Nonaktif' }}
                        </span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight mt-0.5">
                        Edit Pengguna: {{ $user->name }}
                    </h2>
                </div>
            </div>
        </div>

        {{-- ── 2. Split Form Architecture (8 Col Form + 4 Col Interactive Companion) ── --}}
        <form id="editUserForm" method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

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
                                    <p class="text-xs text-slate-500">Nama lengkap, NIK identitas, dan alamat email</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="label label-required">Nama Lengkap</label>
                                <input name="name" type="text" x-model="name"
                                    class="input @error('name') input-error @enderror" required>
                                @error('name')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- NIK (Permanen / Terkunci) --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="label mb-0">NIK Karyawan</label>
                                        <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Terkunci Permanen
                                        </span>
                                    </div>
                                    <input type="text" value="{{ $user->nik }}"
                                        class="input bg-slate-50 text-slate-500 font-mono font-bold tracking-wider cursor-not-allowed"
                                        readonly>
                                    <p class="mt-1 text-[11px] text-slate-400">NIK terikat pada riwayat dokumen dan tidak
                                        dapat diubah</p>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="label label-required">Alamat Email</label>
                                    <input name="email" type="email" x-model="email"
                                        class="input @error('email') input-error @enderror" required>
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
                                        @foreach ($kantor as $k)
                                            <option value="{{ $k->id }}" data-label="{{ $k->nama }}"
                                                @selected(old('kantor_id', $user->kantor_id) == $k->id)>
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
                                    <select name="jabatan_id" class="input @error('jabatan_id') input-error @enderror"
                                        x-model="jabatanId" @change="checkLainnya()" required>
                                        @foreach ($jabatan as $j)
                                            <option value="{{ $j->id }}" data-nama="{{ $j->nama }}"
                                                @selected(old('jabatan_id', $user->jabatan_id) == $j->id)>
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
                                <label class="label">Nama Jabatan Khusus (Lainnya)</label>
                                <input name="jabatan_custom" type="text" x-model="jabatanCustom"
                                    class="input @error('jabatan_custom') input-error @enderror">
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

                    {{-- CARD 4: Status Keaktifan Akun --}}
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
                                    <h3 class="text-sm font-bold text-slate-800">Status Operasional Akun</h3>
                                    <p class="text-xs text-slate-500">Atur status login dan akses pengguna ke dalam sistem
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <label
                                    class="relative flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer transition-all focus:outline-none select-none"
                                    :class="isActive === '1' ? 'border-emerald-500 bg-emerald-50/50 shadow-xs' :
                                        'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" name="is_active" value="1" x-model="isActive"
                                        class="sr-only">
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 mt-0.5 transition-colors"
                                        :class="isActive === '1' ? 'border-emerald-600 bg-emerald-600' :
                                            'border-slate-300 bg-white'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="isActive === '1'"></div>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 block">Akun Aktif</span>
                                        <p class="text-xs text-slate-500 mt-0.5">Pengguna dapat login, mengajukan, atau
                                            memproses dokumen.</p>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-start gap-3.5 p-4 rounded-xl border-2 cursor-pointer transition-all focus:outline-none select-none"
                                    :class="isActive === '0' ? 'border-red-500 bg-red-50/50 shadow-xs' :
                                        'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" name="is_active" value="0" x-model="isActive"
                                        class="sr-only">
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 mt-0.5 transition-colors"
                                        :class="isActive === '0' ? 'border-red-600 bg-red-600' :
                                            'border-slate-300 bg-white'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="isActive === '0'"></div>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 block">Akun Nonaktif</span>
                                        <p class="text-xs text-slate-500 mt-0.5">Akses login diblokir sepenuhnya dari
                                            sistem.</p>
                                    </div>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="field-error mt-2">{{ $message }}</p>
                            @enderror
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
                                x-text="name ? name.toUpperCase() : '{{ $user->name }}'"></h4>
                            <div class="mt-1 flex items-center gap-1.5">
                                <span
                                    class="font-mono text-xs font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                    {{ $user->nik }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 truncate max-w-full"
                                x-text="email ? email : '{{ $user->email }}'"></p>

                            <div
                                class="mt-3 pt-3 border-t border-slate-200/60 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Penempatan:</span>
                                <span class="font-semibold text-slate-700 truncate max-w-[150px]"
                                    x-text="kantorLabel ? kantorLabel : '{{ $user->kantor?->nama ?? '—' }}'"></span>
                            </div>
                            <div class="mt-1 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Jabatan:</span>
                                <span class="font-semibold text-slate-700 truncate max-w-[150px]"
                                    x-text="isLainnya && jabatanCustom ? jabatanCustom : (jabatanNama ? jabatanNama : '{{ $user->jabatan_label }}')"></span>
                            </div>
                            <div class="mt-1 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Role Sistem:</span>
                                <span class="font-bold text-brand-700 uppercase"
                                    x-text="selectedRole ? selectedRole : 'pemohon'"></span>
                            </div>
                            <div class="mt-1 w-full flex items-center justify-between text-xs">
                                <span class="text-slate-400">Status Akun:</span>
                                <span class="font-bold text-xs"
                                    :class="isActive === '1' ? 'text-emerald-600' : 'text-red-600'"
                                    x-text="isActive === '1' ? 'AKTIF' : 'NONAKTIF'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. METADATA AKUN --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3 animate-fade-up"
                        style="animation-delay: 0.1s;">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Metadata Pengguna
                        </h4>
                        <div class="text-xs text-slate-500 space-y-2">
                            <div class="flex items-center justify-between">
                                <span>Terdaftar Sejak:</span>
                                <span
                                    class="font-semibold text-slate-700">{{ $user->created_at->locale('id')->isoFormat('D MMM Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Login Terakhir:</span>
                                <span
                                    class="font-semibold text-slate-700">{{ $user->last_login_at ? $user->last_login_at->locale('id')->diffForHumans() : 'Belum pernah' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Verifikasi Email:</span>
                                <span
                                    class="font-semibold {{ $user->email_verified ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $user->email_verified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. ACTION HUB CARD --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3 animate-fade-up"
                        style="animation-delay: 0.15s;">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-bold text-sm bg-brand-600 text-white hover:bg-brand-700 shadow-md shadow-brand-500/25 transition-all focus:outline-none focus:ring-2 focus:ring-brand-500 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ url()->previous() }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-semibold text-xs text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                            Batalkan & Kembali
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>

    <script>
        function adminEditUserForm() {
            return {
                name: '{{ old('name', $user->name) }}',
                email: '{{ old('email', $user->email) }}',
                kantorId: '{{ old('kantor_id', $user->kantor_id) }}',
                kantorLabel: '',
                jabatanId: '{{ old('jabatan_id', $user->jabatan_id) }}',
                jabatanNama: '',
                jabatanCustom: '{{ old('jabatan_custom', $user->jabatan_custom) }}',
                selectedRole: '{{ old('roles.0', $userRoles[0] ?? 'pemohon') }}',
                isActive: '{{ old('is_active', $user->is_active ? '1' : '0') }}',
                isLainnya: false,
                jabatans: @json($jabatan->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama, 'is_lainnya' => $j->is_lainnya])),

                init() {
                    this.updateKantorLabel();
                    this.checkLainnya();
                },

                updateKantorLabel() {
                    const select = document.querySelector('select[name="kantor_id"]');
                    if (select && select.selectedIndex >= 0) {
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

                submitForm() {
                    document.getElementById('editUserForm').submit();
                }
            }
        }
    </script>
@endsection
