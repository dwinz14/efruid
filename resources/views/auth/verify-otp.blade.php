@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-brand-100 rounded-full mb-3">
            <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-900">Verifikasi Email</h2>
        <p class="text-sm text-slate-500 mt-1">
            Kode OTP 6 digit telah dikirim ke<br>
            <strong class="text-slate-700">{{ auth()->user()->email }}</strong>
        </p>
    </div>

    @if(session('success'))
        <div class="alert-success mb-4">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="mb-6">
            <label for="otp" class="label label-required">Kode OTP</label>
            <input
                id="otp" name="otp" type="text"
                inputmode="numeric" pattern="\d{6}" maxlength="6"
                class="input text-center text-2xl font-bold tracking-widest @error('otp') input-error @enderror"
                placeholder="000000"
                autofocus autocomplete="one-time-code"
                required
            >
            @error('otp') <p class="field-error text-center">{{ $message }}</p> @enderror
            <p class="text-xs text-slate-400 text-center mt-2">Kode berlaku selama 10 menit</p>
        </div>

        <button type="submit" class="btn-primary w-full btn-lg mb-4" :disabled="loading">
            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span x-text="loading ? 'Memverifikasi...' : 'Verifikasi'">Verifikasi</span>
        </button>
    </form>

    {{-- Resend OTP dengan countdown --}}
    <div
        x-data="{
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
        }"
        x-init="start()"
        class="text-center"
    >
        <p class="text-sm text-slate-500">
            Tidak menerima kode?
        </p>
        <form method="POST" action="{{ route('verification.resend') }}" class="inline">
            @csrf
            <button
                type="submit"
                class="text-sm font-medium mt-1 transition-colors"
                :class="seconds > 0 ? 'text-slate-400 cursor-not-allowed' : 'text-brand-600 hover:text-brand-700'"
                :disabled="seconds > 0"
            >
                <span x-show="seconds > 0">Kirim ulang dalam <span x-text="seconds"></span> detik</span>
                <span x-show="seconds <= 0">Kirim ulang kode</span>
            </button>
        </form>
    </div>

    <div class="mt-6 pt-4 border-t border-slate-200 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-400 hover:text-slate-600">
                Masuk dengan akun lain
            </button>
        </form>
    </div>
@endsection
