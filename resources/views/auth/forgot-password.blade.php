@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Lupa Password</h1>
        <p class="mt-1.5 text-sm text-slate-500">Masukkan email Anda. Kami akan mengirimkan kode OTP untuk reset password.</p>
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

    <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf
        <div class="mb-6">
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
                    required autofocus>
            </div>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="mb-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-150 hover:from-brand-700 hover:to-brand-800 hover:shadow-brand-600/35 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="loading">
            <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span x-text="loading ? 'Mengirim...' : 'Kirim Kode OTP'">Kirim Kode OTP</span>
        </button>
    </form>

    <p class="text-center text-sm text-slate-500">
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-1.5 font-semibold text-brand-600 transition-colors hover:text-brand-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Kembali ke Login
        </a>
    </p>
@endsection
