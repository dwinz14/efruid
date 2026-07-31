@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="text-xl font-bold text-slate-900 mb-1">Masuk ke eFRUID</h2>
    <p class="text-sm text-slate-500 mb-6">Formulir Registrasi User ID — BPR Artha Pamenang</p>

    @if (session('success'))
        <div class="alert-success mb-4">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}"
                class="input @error('email') input-error @enderror" placeholder="nama@artha-pamenang.co.id" required
                autofocus>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4" x-data="{ show: false }">
            <label for="password" class="label">Password</label>
            <div class="relative">
                <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password"
                    class="input pr-10 @error('password') input-error @enderror" placeholder="••••••••" required>
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

        {{-- Remember me --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Ingat saya
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                Lupa password?
            </a>
        </div>

        <button type="submit" class="btn-primary w-full btn-lg" :disabled="loading">
            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span x-text="loading ? 'Memproses...' : 'Masuk'">Masuk</span>
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand-600 hover:text-brand-700 font-medium">Daftar di sini</a>
    </p>
@endsection
