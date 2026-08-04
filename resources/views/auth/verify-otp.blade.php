@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="mb-7 text-center">
        <div
            class="mx-auto mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 ring-1 ring-brand-100">
            <svg class="h-7 w-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verifikasi Email</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
            Kode OTP 6 digit telah dikirim ke<br>
            <strong class="font-semibold text-slate-700">{{ auth()->user()->email }}</strong>
        </p>
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

    <form method="POST" action="{{ route('verification.verify') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="mb-6">
            <label for="otp" class="label label-required">Kode OTP</label>
            <input id="otp" name="otp" type="text" inputmode="numeric" pattern="\d{6}" maxlength="6"
                class="input py-3.5 text-center text-2xl font-bold tracking-[0.5em] @error('otp') input-error @enderror"
                placeholder="000000" autofocus autocomplete="one-time-code" required
                aria-invalid="@error('otp') true @else false @enderror">
            @error('otp')
                <p class="field-error text-center">{{ $message }}</p>
            @enderror
            <p class="mt-2.5 flex items-center justify-center gap-1.5 text-xs text-slate-400">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Kode berlaku selama 10 menit
            </p>
        </div>

        <button type="submit"
            class="mb-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-150 hover:from-brand-700 hover:to-brand-800 hover:shadow-brand-600/35 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="loading">
            <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            <span x-text="loading ? 'Memverifikasi...' : 'Verifikasi'">Verifikasi</span>
        </button>
    </form>

    {{-- Resend OTP dengan countdown --}}
    <div x-data="{
        seconds: {{ $cooldown }},
        timer: null,
        start() {
            if (this.seconds <= 0) return;
            this.timer = setInterval(() => {
                this.seconds--;
                if (this.seconds <= 0) {
                    clearInterval(this.timer);
                }
            }, 1000);
        }
    }" x-init="start()" class="rounded-xl bg-slate-50 px-4 py-3 text-center ring-1 ring-slate-100">
        <p class="text-sm text-slate-500">Tidak menerima kode?</p>
        <form method="POST" action="{{ route('verification.resend') }}" class="inline">
            @csrf
            <button type="submit"
                class="mt-1 text-sm font-semibold transition-colors"
                :class="seconds > 0 ? 'cursor-not-allowed text-slate-400' : 'text-brand-600 hover:text-brand-700'"
                :disabled="seconds > 0">
                <span x-show="seconds > 0">Kirim ulang dalam <span class="tabular-nums" x-text="seconds"></span> detik</span>
                <span x-show="seconds <= 0">Kirim ulang kode</span>
            </button>
        </form>
    </div>

    <div class="mt-6 border-t border-slate-100 pt-5 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-1.5 text-sm text-slate-400 transition-colors hover:text-slate-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Masuk dengan akun lain
            </button>
        </form>
    </div>
@endsection
