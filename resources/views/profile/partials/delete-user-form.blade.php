<section class="space-y-5">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="text-base font-bold text-red-700">
            {{ __('Hapus Akun Pengguna') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
            {{ __('Setelah akun dihapus, seluruh sumber daya dan data yang terkait akan dihapus secara permanen. Pastikan Anda telah mengunduh data atau informasi yang ingin Anda simpan sebelum melanjutkan.') }}
        </p>
    </header>

    <div>
        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="btn-danger text-xs sm:text-sm px-4 py-2.5 shadow-xs">
            {{ __('Hapus Akun Ini') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-base font-bold text-slate-900">
                        {{ __('Apakah Anda yakin ingin menghapus akun ini?') }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                        {{ __('Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan permanen.') }}
                    </p>
                </div>
            </div>

            <div class="mt-5">
                <label for="delete_account_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                    {{ __('Konfirmasi Password') }} <span class="text-red-500">*</span>
                </label>
                <input id="delete_account_password" name="password" type="password" class="input w-full text-xs sm:text-sm py-2.5"
                    placeholder="{{ __('Masukkan password untuk konfirmasi') }}" required>
                @if ($errors->userDeletion->get('password'))
                    <p class="field-error mt-1">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-2.5">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs sm:text-sm px-4 py-2">
                    {{ __('Batal') }}
                </button>
                <button type="submit" class="btn-danger text-xs sm:text-sm px-4 py-2 shadow-xs">
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
