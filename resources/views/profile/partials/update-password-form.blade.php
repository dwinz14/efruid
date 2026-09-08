<section class="space-y-5">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="text-base font-bold text-slate-800">
            {{ __('Perbarui Password') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div x-data="{ show: false }">
            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="update_password_current_password">
                {{ __('Password Saat Ini') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" :type="show ? 'text' : 'password'"
                    class="input w-full text-xs sm:text-sm py-2.5 pr-10" autocomplete="current-password" required>
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors"
                    tabindex="-1">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @if ($errors->updatePassword->get('current_password'))
                <p class="field-error mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        {{-- New Password --}}
        <div x-data="{ show: false }">
            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="update_password_password">
                {{ __('Password Baru') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input id="update_password_password" name="password" :type="show ? 'text' : 'password'"
                    class="input w-full text-xs sm:text-sm py-2.5 pr-10" autocomplete="new-password" required>
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors"
                    tabindex="-1">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @if ($errors->updatePassword->get('password'))
                <p class="field-error mt-1">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        {{-- Confirm Password --}}
        <div x-data="{ show: false }">
            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="update_password_password_confirmation">
                {{ __('Konfirmasi Password Baru') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'"
                    class="input w-full text-xs sm:text-sm py-2.5 pr-10" autocomplete="new-password" required>
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors"
                    tabindex="-1">
                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="field-error mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn-primary text-xs sm:text-sm px-5 py-2.5 shadow-xs">
                {{ __('Perbarui Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-xs font-bold text-emerald-600">
                    {{ __('Password Berhasil Diperbarui.') }}
                </p>
            @endif
        </div>
    </form>
</section>
