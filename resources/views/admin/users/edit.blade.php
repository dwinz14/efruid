@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    Edit: {{ $user->name }}
                </h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" x-data="adminUserForm()">

                    <script>
                        function adminUserForm() {
                            return {
                                jabatanId: '{{ old('jabatan_id', $user->jabatan_id) }}',
                                isLainnya: false,
                                jabatan: @json($jabatan->map(fn($j) => ['id' => $j->id, 'is_lainnya' => $j->is_lainnya])),
                                init() {
                                    this.checkLainnya();
                                },
                                checkLainnya() {
                                    const found = this.jabatan.find(j => String(j.id) === String(this.jabatanId));
                                    this.isLainnya = found ? found.is_lainnya : false;
                                }
                            }
                        }
                    </script>

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label class="label label-required">Nama Lengkap</label>
                            <input name="name" type="text" value="{{ old('name', $user->name) }}"
                                class="input @error('name') input-error @enderror" required>
                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label">NIK</label>
                            <input type="text" value="{{ $user->nik }}"
                                class="input bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                            <p class="mt-1 text-xs text-slate-400">NIK tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="label label-required">Email</label>
                            <input name="email" type="email" value="{{ old('email', $user->email) }}"
                                class="input @error('email') input-error @enderror" required>
                            @error('email')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label label-required">Kantor</label>
                            <select name="kantor_id" class="input" required>
                                @foreach ($kantor as $k)
                                    <option value="{{ $k->id }}" @selected(old('kantor_id', $user->kantor_id) == $k->id)>
                                        {{ $k->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="label label-required">Jabatan</label>
                            <select name="jabatan_id" class="input" x-model="jabatanId" @change="checkLainnya()" required>
                                @foreach ($jabatan as $j)
                                    <option value="{{ $j->id }}" @selected(old('jabatan_id', $user->jabatan_id) == $j->id)>
                                        {{ $j->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2" x-show="isLainnya" x-transition>
                            <label class="label">Nama Jabatan</label>
                            <input name="jabatan_custom" type="text"
                                value="{{ old('jabatan_custom', $user->jabatan_custom) }}" class="input">
                        </div>

                        <div>
                            <label class="label label-required">Status Akun</label>
                            <select name="is_active" class="input" required>
                                <option value="1" @selected(old('is_active', $user->is_active ? '1' : '0') === '1')>
                                    Aktif
                                </option>
                                <option value="0" @selected(old('is_active', $user->is_active ? '1' : '0') === '0')>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="label label-required">Role</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1">
                                @foreach ($roles as $role)
                                    <label
                                        class="flex items-center gap-2 p-2 border rounded-lg
                                              cursor-pointer hover:bg-slate-50 transition-colors">
                                        <input type="checkbox" name="roles[]" value="{{ $role->value }}"
                                            @checked(in_array($role->value, old('roles', $userRoles)))
                                            class="rounded text-brand-600 focus:ring-brand-500">
                                        <span class="text-sm text-slate-700">{{ $role->label() }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-6 flex gap-3 justify-end">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
