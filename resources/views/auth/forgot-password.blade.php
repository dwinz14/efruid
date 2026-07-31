@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Lupa Password</h2>
        <p class="text-sm text-slate-500 mt-1">Masukkan email Anda. Kami akan mengirimkan kode OTP untuk reset password.</p>
    </div>

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

    <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf
        <div class="mb-6">
            <label for="email" class="label label-required">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                class="input @error('email') input-error @enderror" placeholder="nama@artha-pamenang.co.id" required
                autofocus>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full btn-lg mb-4" :disabled="loading">
            <span x-text="loading ? 'Mengirim...' : 'Kirim Kode OTP'">Kirim Kode OTP</span>
        </button>
    </form>

    <p class="text-center text-sm text-slate-500">
        <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-medium">← Kembali ke Login</a>
    </p>
@endsection
