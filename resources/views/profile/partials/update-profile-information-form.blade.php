<section class="space-y-5">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="text-base font-bold text-slate-800">
            {{ __('Informasi Data Diri') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            {{ __('Perbarui data profil akun Anda dan alamat email yang terdaftar.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        {{-- Nama --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="name">{{ __('Nama Lengkap') }} <span class="text-red-500">*</span></label>
            <input id="name" name="name" type="text" class="input w-full text-xs sm:text-sm py-2.5" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="field-error mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="email">{{ __('Alamat Email') }} <span class="text-red-500">*</span></label>
            <input id="email" name="email" type="email" class="input w-full text-xs sm:text-sm py-2.5" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <p class="field-error mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                    <p class="font-medium">
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                        <button form="send-verification" class="underline font-bold text-brand-600 hover:text-brand-800 focus:outline-none">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-emerald-600">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn-primary text-xs sm:text-sm px-5 py-2.5 shadow-xs">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-xs font-bold text-emerald-600">
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
