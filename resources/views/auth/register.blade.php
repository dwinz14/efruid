@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Buat Akun Baru</h1>
        <p class="mt-1.5 text-sm text-slate-500">Lengkapi data di bawah ini untuk mendaftar di eFRUID.</p>
    </div>

    <script>
        function registerForm() {
            return {
                loading: false,
                show: false,
                showConfirm: false,
                password: '',
                strength: 0,
                jabatanId: '{{ old('jabatan_id', '') }}',
                isLainnya: false,
                jabatans: @json($jabatans->map(fn($j) => ['id' => $j->id, 'is_lainnya' => $j->is_lainnya])),
                init() {
                    this.checkLainnya();
                },
                checkLainnya() {
                    const found = this.jabatans.find(j => String(j.id) === String(this.jabatanId));
                    this.isLainnya = found ? found.is_lainnya : false;
                },
                updateStrength() {
                    let score = 0;
                    if (this.password.length >= 8) score++;
                    if (/[A-Za-z]/.test(this.password)) score++;
                    if (/\d/.test(this.password)) score++;
                    this.strength = score;
                },
                barClass(n) {
                    if (this.strength < n) return 'bg-slate-200';
                    if (this.strength === 1) return 'bg-red-500';
                    if (this.strength === 2) return 'bg-amber-500';
                    return 'bg-green-500';
                },
                strengthLabel() {
                    if (this.strength === 0) return '';
                    if (this.strength === 1) return 'Password lemah — gunakan minimal 8 karakter, huruf, dan angka.';
                    if (this.strength === 2) return 'Cukup baik — tambahkan huruf dan angka agar lebih kuat.';
                    return 'Kuat — password Anda sudah aman.';
                },
                strengthLabelClass() {
                    if (this.strength === 1) return 'text-red-500';
                    if (this.strength === 2) return 'text-amber-600';
                    return 'text-green-600';
                }
            }
        }
    </script>

    <form method="POST" action="{{ route('register') }}" x-data="registerForm()" @submit="loading = true">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="label label-required">Nama Lengkap</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400 @error('name') text-red-400 @enderror" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                        class="input pl-10 @error('name') input-error @enderror" placeholder="Sesuai identitas resmi"
                        required>
                </div>
                @error('name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIK --}}
            <div>
                <label for="nik" class="label label-required">NIK Karyawan</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400 @error('nik') text-red-400 @enderror" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </div>
                    <input id="nik" name="nik" type="text" value="{{ old('nik') }}"
                        class="input pl-10 @error('nik') input-error @enderror" placeholder="AP000000000" maxlength="11"
                        required>
                </div>
                <p class="mt-1 text-xs text-slate-400">Format: AP diikuti 9 digit angka (contoh: AP000123456)</p>
                @error('nik')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <label for="email" class="label label-required">Email</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-5 w-5 text-slate-400 @error('email') text-red-400 @enderror" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                    class="input pl-10 @error('email') input-error @enderror" placeholder="nama@artha-pamenang.co.id"
                    required>
            </div>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            {{-- Kantor --}}
            <div>
                <label for="kantor_id" class="label label-required">Kantor</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400 @error('kantor_id') text-red-400 @enderror" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <select id="kantor_id" name="kantor_id" class="input pl-10 @error('kantor_id') input-error @enderror"
                        required>
                        <option value="">— Pilih Kantor —</option>
                        @foreach ($kantors as $kantor)
                            <option value="{{ $kantor->id }}" @selected(old('kantor_id') == $kantor->id)>
                                {{ $kantor->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('kantor_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div>
                <label for="jabatan_id" class="label label-required">Jabatan</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400 @error('jabatan_id') text-red-400 @enderror" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <select id="jabatan_id" name="jabatan_id" class="input pl-10 @error('jabatan_id') input-error @enderror"
                        x-model="jabatanId" @change="checkLainnya()" required>
                        <option value="">— Pilih Jabatan —</option>
                        @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" @selected(old('jabatan_id') == $jabatan->id)>
                                {{ $jabatan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('jabatan_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Jabatan Custom (hanya jika LAINNYA) --}}
        <div class="mt-4" x-show="isLainnya" x-transition>
            <label for="jabatan_custom" class="label label-required">Nama Jabatan</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-5 w-5 text-slate-400 @error('jabatan_custom') text-red-400 @enderror" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <input id="jabatan_custom" name="jabatan_custom" type="text" value="{{ old('jabatan_custom') }}"
                    class="input pl-10 @error('jabatan_custom') input-error @enderror" placeholder="Tulis jabatan Anda"
                    :required="isLainnya">
            </div>
            @error('jabatan_custom')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            {{-- Password --}}
            <div>
                <label for="password" class="label label-required">Password</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400 @error('password') text-red-400 @enderror" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <input id="password" name="password" :type="show ? 'text' : 'password'" x-model="password"
                        @input="updateStrength()" class="input pl-10 pr-11 @error('password') input-error @enderror"
                        placeholder="Min. 8 karakter, huruf + angka" required>
                    <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition-colors hover:text-brand-600"
                        :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'" aria-label="Tampilkan password">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>

                {{-- Strength meter --}}
                <div class="mt-2" x-show="password.length > 0" x-transition>
                    <div class="flex gap-1.5">
                        <div class="h-1.5 flex-1 rounded-full transition-colors duration-200" :class="barClass(1)"></div>
                        <div class="h-1.5 flex-1 rounded-full transition-colors duration-200" :class="barClass(2)"></div>
                        <div class="h-1.5 flex-1 rounded-full transition-colors duration-200" :class="barClass(3)"></div>
                    </div>
                    <p class="mt-1.5 text-xs" :class="strengthLabelClass()" x-text="strengthLabel()"></p>
                </div>

                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="label label-required">Konfirmasi Password</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <input id="password_confirmation" name="password_confirmation"
                        :type="showConfirm ? 'text' : 'password'" class="input pl-10 pr-11" placeholder="Ulangi password"
                        required>
                    <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                        class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition-colors hover:text-brand-600"
                        :aria-label="showConfirm ? 'Sembunyikan password' : 'Tampilkan password'"
                        aria-label="Tampilkan password">
                        <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-150 hover:from-brand-700 hover:to-brand-800 hover:shadow-brand-600/35 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="loading">
            <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span x-text="loading ? 'Mendaftar...' : 'Daftar Sekarang'">Daftar Sekarang</span>
            <svg x-show="!loading" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </button>
    </form>

    <div class="mt-6 border-t border-slate-100 pt-6 text-center">
        <p class="text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="font-semibold text-brand-600 transition-colors hover:text-brand-700">Masuk di sini</a>
        </p>
    </div>
@endsection
