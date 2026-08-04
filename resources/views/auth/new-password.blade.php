@extends('layouts.auth')

@section('title', 'Buat Password Baru')

@section('content')
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Buat Password Baru</h1>
        <p class="mt-1.5 text-sm text-slate-500">Password minimal 8 karakter, kombinasi huruf dan angka.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}"
        x-data="{ loading: false, show: false, showConfirm: false, password: '', strength: 0,
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
        }" @submit="loading = true">
        @csrf

        <div class="mb-4">
            <label for="password" class="label label-required">Password Baru</label>
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

        <div class="mb-6">
            <label for="password_confirmation" class="label label-required">Konfirmasi Password</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                    class="input pl-10 pr-11" placeholder="Ulangi password baru" required>
                <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                    class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition-colors hover:text-brand-600"
                    :aria-label="showConfirm ? 'Sembunyikan password' : 'Tampilkan password'" aria-label="Tampilkan password">
                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-150 hover:from-brand-700 hover:to-brand-800 hover:shadow-brand-600/35 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="loading">
            <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span x-text="loading ? 'Menyimpan...' : 'Simpan Password Baru'">Simpan Password Baru</span>
        </button>
    </form>
@endsection
