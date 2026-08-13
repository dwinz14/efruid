@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h2 class="text-sm font-semibold text-slate-800">Form Tambah User Manual</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                User yang dibuat manual langsung aktif dan tidak perlu verifikasi email.
            </p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}"
                  x-data="adminUserForm()">

                <script>
                function adminUserForm() {
                    return {
                        jabatanId: '',
                        isLainnya: false,
                        jabatan: @json($jabatan->map(fn($j) => ['id' => $j->id, 'is_lainnya' => $j->is_lainnya])),
                        init() { this.checkLainnya(); },
                        checkLainnya() {
                            const found = this.jabatan.find(j => String(j.id) === String(this.jabatanId));
                            this.isLainnya = found ? found.is_lainnya : false;
                        }
                    }
                }
                </script>

                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="sm:col-span-2">
                        <label class="label label-required">Nama Lengkap</label>
                        <input name="name" type="text" value="{{ old('name') }}"
                               class="input @error('name') input-error @enderror"
                               placeholder="Nama sesuai identitas" required>
                        @error('name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label label-required">NIK</label>
                        <input name="nik" type="text" value="{{ old('nik') }}"
                               class="input @error('nik') input-error @enderror"
                               placeholder="AP000000000" maxlength="11" required>
                        @error('nik') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label label-required">Email</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="input @error('email') input-error @enderror"
                               required>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label label-required">Kantor</label>
                        <select name="kantor_id"
                                class="input @error('kantor_id') input-error @enderror" required>
                            <option value="">— Pilih —</option>
                            @foreach($kantor as $k)
                                <option value="{{ $k->id }}"
                                    @selected(old('kantor_id') == $k->id)>
                                    {{ $k->label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kantor_id') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label label-required">Jabatan</label>
                        <select name="jabatan_id"
                                class="input @error('jabatan_id') input-error @enderror"
                                x-model="jabatanId" @change="checkLainnya()" required>
                            <option value="">— Pilih —</option>
                            @foreach($jabatan as $j)
                                <option value="{{ $j->id }}"
                                    @selected(old('jabatan_id') == $j->id)>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2" x-show="isLainnya" x-transition>
                        <label class="label label-required">Nama Jabatan</label>
                        <input name="jabatan_custom" type="text"
                               value="{{ old('jabatan_custom') }}"
                               class="input" :required="isLainnya">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="label label-required">Password</label>
                        <input name="password" type="password"
                               class="input @error('password') input-error @enderror"
                               placeholder="Min. 8 karakter, huruf + angka" required>
                        <p class="mt-1 text-xs text-slate-400">
                            Sampaikan password ke user secara langsung.
                        </p>
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="label label-required">Role</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2 p-2 border rounded-lg
                                              cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="roles[]"
                                           value="{{ $role->value }}"
                                           @checked(in_array($role->value, old('roles', ['pemohon'])))
                                           class="rounded text-brand-600 focus:ring-brand-500">
                                    <span class="text-sm text-slate-700">{{ $role->label() }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
