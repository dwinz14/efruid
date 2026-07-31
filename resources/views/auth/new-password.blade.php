@extends('layouts.auth')

@section('title', 'Buat Password Baru')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Buat Password Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Password minimal 8 karakter, kombinasi huruf dan angka.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" x-data="{ loading: false, show: false, showConfirm: false }" @submit="loading = true">
        @csrf

        <div class="mb-4">
            <label for="password" class="label label-required">Password Baru</label>
            <div class="relative">
                <input id="password" name="password" :type="show ? 'text' : 'password'"
                    class="input pr-10 @error('password') input-error @enderror"
                    placeholder="Min. 8 karakter, huruf + angka" required>
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                    tabindex="-1">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="label label-required">Konfirmasi Password</label>
            <div class="relative">
                <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                    class="input pr-10" placeholder="Ulangi password baru" required>
                <button type="button" @click="showConfirm = !showConfirm"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                    tabindex="-1">
                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full btn-lg" :disabled="loading">
            <span x-text="loading ? 'Menyimpan...' : 'Simpan Password Baru'">Simpan Password Baru</span>
        </button>
    </form>
@endsection
