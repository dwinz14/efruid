@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
    <h2 class="text-xl font-bold text-slate-900 mb-1">Daftar Akun</h2>
    <p class="text-sm text-slate-500 mb-6">Buat akun eFRUID Anda</p>

<script>
    function registerForm() {
        return {
            loading: false,
            jabatanId: '{{ old('jabatan_id', '') }}',
            isLainnya: false,
            jabatans: @json($jabatans->map(fn($j) => ['id' => $j->id, 'is_lainnya' => $j->is_lainnya])),
            init() {
                this.checkLainnya();
            },
            checkLainnya() {
                const found = this.jabatans.find(j => String(j.id) === String(this.jabatanId));
                this.isLainnya = found ? found.is_lainnya : false;
            }
        }
    }
</script>

<form
    method="POST"
    action="{{ route('register') }}"
    x-data="registerForm()"
    @submit="loading = true"
>
        @csrf

        {{-- Nama Lengkap --}}
        <div class="mb-4">
            <label for="name" class="label label-required">Nama Lengkap</label>
            <input
                id="name" name="name" type="text"
                value="{{ old('name') }}"
                class="input @error('name') input-error @enderror"
                placeholder="Sesuai identitas resmi"
                required
            >
            @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- NIK --}}
        <div class="mb-4">
            <label for="nik" class="label label-required">NIK Karyawan</label>
            <input
                id="nik" name="nik" type="text"
                value="{{ old('nik') }}"
                class="input @error('nik') input-error @enderror"
                placeholder="AP000000000"
                maxlength="11"
                required
            >
            <p class="mt-1 text-xs text-slate-400">Format: AP diikuti 9 digit angka (contoh: AP000123456)</p>
            @error('nik') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="label label-required">Email</label>
            <input
                id="email" name="email" type="email"
                value="{{ old('email') }}"
                class="input @error('email') input-error @enderror"
                placeholder="nama@artha-pamenang.co.id"
                required
            >
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Kantor --}}
        <div class="mb-4">
            <label for="kantor_id" class="label label-required">Kantor</label>
            <select id="kantor_id" name="kantor_id" class="input @error('kantor_id') input-error @enderror" required>
                <option value="">— Pilih Kantor —</option>
                @foreach($kantors as $kantor)
                    <option value="{{ $kantor->id }}" @selected(old('kantor_id') == $kantor->id)>
                        {{ $kantor->label }}
                    </option>
                @endforeach
            </select>
            @error('kantor_id') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Jabatan --}}
        <div class="mb-4">
            <label for="jabatan_id" class="label label-required">Jabatan</label>
            <select
                id="jabatan_id" name="jabatan_id"
                class="input @error('jabatan_id') input-error @enderror"
                x-model="jabatanId"
                @change="checkLainnya()"
                required
            >
                <option value="">— Pilih Jabatan —</option>
                @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" @selected(old('jabatan_id') == $jabatan->id)>
                        {{ $jabatan->nama }}
                    </option>
                @endforeach
            </select>
            @error('jabatan_id') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Jabatan Custom (hanya jika LAINNYA) --}}
        <div class="mb-4" x-show="isLainnya" x-transition>
            <label for="jabatan_custom" class="label label-required">Nama Jabatan</label>
            <input
                id="jabatan_custom" name="jabatan_custom" type="text"
                value="{{ old('jabatan_custom') }}"
                class="input @error('jabatan_custom') input-error @enderror"
                placeholder="Tulis jabatan Anda"
                :required="isLainnya"
            >
            @error('jabatan_custom') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4" x-data="{ show: false }">
            <label for="password" class="label label-required">Password</label>
            <div class="relative">
                <input
                    id="password" name="password"
                    :type="show ? 'text' : 'password'"
                    class="input pr-10 @error('password') input-error @enderror"
                    placeholder="Min. 8 karakter, huruf + angka"
                    required
                >
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                    tabindex="-1">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="label label-required">Konfirmasi Password</label>
            <input
                id="password_confirmation" name="password_confirmation" type="password"
                class="input"
                placeholder="Ulangi password"
                required
            >
        </div>

        <button type="submit" class="btn-primary w-full btn-lg" :disabled="loading">
            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span x-text="loading ? 'Mendaftar...' : 'Daftar Sekarang'">Daftar Sekarang</span>
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-medium">Masuk di sini</a>
    </p>
@endsection
