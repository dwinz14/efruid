@extends('layouts.app')

@section('title', 'Kelola Kantor')
@section('page-title', 'Kelola Kantor')

@section('content')
    <div class="max-w-4xl mx-auto space-y-4">

        {{-- Form tambah --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">Tambah Kantor</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.kantor.store') }}" class="flex gap-3 items-end flex-wrap">
                    @csrf
                    <div>
                        <label class="label label-required">Nama Kantor</label>
                        <input name="nama" type="text" value="{{ old('nama') }}"
                            class="input w-44 @error('nama') input-error @enderror" placeholder="CONTOH" required>
                        @error('nama')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label label-required">Kode</label>
                        <input name="kode" type="text" value="{{ old('kode') }}"
                            class="input w-24 font-mono @error('kode') input-error @enderror" placeholder="CNT"
                            maxlength="10" required>
                        <p class="mt-1 text-xs text-slate-400">Huruf/angka, maks 10</p>
                        @error('kode')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-2 pb-1">
                        <input type="checkbox" name="is_pusat" id="is_pusat" value="1" @checked(old('is_pusat'))
                            class="rounded text-brand-600 focus:ring-brand-500">
                        <label for="is_pusat" class="text-sm text-slate-700">Kantor Pusat</label>
                    </div>
                    <button type="submit" class="btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        {{-- Daftar kantor --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    Daftar Kantor ({{ $kantors->total() }})
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto-style">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>User</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kantors as $kantor)
                            <tr x-data="{ edit: false }">
                                <td>
                                    <div x-show="!edit" class="font-medium">{{ $kantor->nama }}</div>
                                    <div x-show="edit" x-transition>
                                        <form method="POST" action="{{ route('admin.kantor.update', $kantor) }}"
                                            class="flex gap-2 items-center flex-wrap">
                                            @csrf @method('PUT')
                                            <input name="nama" type="text" value="{{ $kantor->nama }}"
                                                class="input w-36 text-sm" required>
                                            <input name="kode" type="text" value="{{ $kantor->kode }}"
                                                class="input w-20 font-mono text-sm" maxlength="10" required>
                                            <select name="is_active" class="input w-24 text-sm">
                                                <option value="1" @selected($kantor->is_active)>Aktif</option>
                                                <option value="0" @selected(!$kantor->is_active)>Nonaktif</option>
                                            </select>
                                            <button type="submit" class="btn-primary btn-sm">
                                                Simpan
                                            </button>
                                            <button type="button" @click="edit = false"
                                                class="btn-ghost btn-sm">Batal</button>
                                        </form>
                                    </div>
                                </td>
                                <td x-show="!edit" class="font-mono text-sm">
                                    {{ $kantor->kode }}
                                </td>
                                <td x-show="!edit">
                                    @if ($kantor->is_pusat)
                                        <span class="badge badge-executed">Pusat</span>
                                    @else
                                        <span class="badge badge-draft">Cabang</span>
                                    @endif
                                </td>
                                <td x-show="!edit" class="text-sm">
                                    {{ $kantor->users_count }} user
                                </td>
                                <td x-show="!edit">
                                    @if ($kantor->is_active)
                                        <span class="badge badge-approved">Aktif</span>
                                    @else
                                        <span class="badge badge-cancelled">Nonaktif</span>
                                    @endif
                                </td>
                                <td x-show="!edit" class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="edit = true"
                                            class="btn-secondary btn-sm">Edit</button>
                                        @if ($kantor->users_count === 0)
                                            <form method="POST" action="{{ route('admin.kantor.destroy', $kantor) }}"
                                                onsubmit="return confirm('Hapus kantor ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($kantors->hasPages())
                <div class="px-4 py-3 border-t border-surface-border">
                    {{ $kantors->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
