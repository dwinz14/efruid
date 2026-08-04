@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Selamat datang</h1>
        <p class="mt-1.5 text-sm text-slate-500">Masuk ke akun eFRUID Anda untuk melanjutkan.</p>
    </div>

    @if (session('success'))
        <div class="alert-success mb-5" role="alert">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="label">Email</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-5 w-5 text-slate-400 @error('email') text-red-400 @enderror" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}"
                    class="input pl-10 @error('email') input-error @enderror" placeholder="nama@artha-pamenang.co.id"
                    required autofocus aria-invalid="@error('email') true @else false @enderror">
            </div>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-5" x-data="{ show: false }">
            <label for="password" class="label">Password</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-5 w-5 text-slate-400 @error('password') text-red-400 @enderror" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password"
                    class="input pl-10 pr-11 @error('password') input-error @enderror" placeholder="Masukkan password"
                    required aria-invalid="@error('password') true @else false @enderror">
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
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me & forgot password --}}
        <div class="mb-6 flex items-center justify-between">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember"
                    class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 focus:ring-offset-0">
                Ingat saya
            </label>
            <a href="{{ route('password.request') }}"
                class="text-sm font-semibold text-brand-600 transition-colors hover:text-brand-700">
                Lupa password?
            </a>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-150 hover:from-brand-700 hover:to-brand-800 hover:shadow-brand-600/35 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="loading">
            <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span x-text="loading ? 'Memproses...' : 'Masuk'">Masuk</span>
            <svg x-show="!loading" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </button>
    </form>

    {{-- Register link --}}
    <div class="mt-6 border-t border-slate-100 pt-6 text-center">
        <p class="text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-semibold text-brand-600 transition-colors hover:text-brand-700">Daftar di sini</a>
        </p>
    </div>
@endsection
