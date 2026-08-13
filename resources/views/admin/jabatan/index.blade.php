@extends('layouts.app')

@section('title', 'Kelola Jabatan')
@section('page-title', 'Kelola Jabatan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-4">

        {{-- Form tambah --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">Tambah Jabatan</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.jabatan.store') }}" class="flex gap-3 items-end flex-wrap">
                    @csrf
                    <div>
                        <label class="label label-required">Nama Jabatan</label>
                        <input name="nama" type="text" value="{{ old('nama') }}"
                            class="input w-64 @error('nama') input-error @enderror" placeholder="NAMA JABATAN" required>
                        @error('nama')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label label-required">Urutan</label>
                        <input name="urutan" type="number" value="{{ old('urutan', 50) }}" class="input w-20"
                            min="0" max="999" required>
                    </div>
                    <button type="submit" class="btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        {{-- Daftar jabatan --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-sm font-semibold text-slate-800">
                    Daftar Jabatan ({{ $jabatans->total() }})
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto-style">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nama Jabatan</th>
                            <th>Urutan</th>
                            <th>User</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jabatans as $i => $jabatan)
                            <tr x-data="{ edit: false }">
                                <td class="text-slate-400 text-sm">
                                    {{ $jabatans->firstItem() + $i }}
                                </td>
                                <td>
                                    <div x-show="!edit" class="font-medium">
                                        {{ $jabatan->nama }}
                                        @if ($jabatan->is_lainnya)
                                            <span class="badge badge-pending ml-1 text-xs">Lainnya</span>
                                        @endif
                                    </div>
                                    <div x-show="edit" x-transition>
                                        <form method="POST" action="{{ route('admin.jabatan.update', $jabatan) }}"
                                            class="flex gap-2 items-center flex-wrap">
                                            @csrf @method('PUT')
                                            <input name="nama" type="text" value="{{ $jabatan->nama }}"
                                                class="input w-56 text-sm" required>
                                            <input name="urutan" type="number" value="{{ $jabatan->urutan }}"
                                                class="input w-16 text-sm" min="0">
                                            <select name="is_active" class="input w-24 text-sm">
                                                <option value="1" @selected($jabatan->is_active)>
                                                    Aktif
                                                </option>
                                                <option value="0" @selected(!$jabatan->is_active)>
                                                    Nonaktif
                                                </option>
                                            </select>
                                            <button type="submit" class="btn-primary btn-sm">
                                                Simpan
                                            </button>
                                            <button type="button" @click="edit = false"
                                                class="btn-ghost btn-sm">Batal</button>
                                        </form>
                                    </div>
                                </td>
                                <td x-show="!edit" class="text-sm text-slate-500">
                                    {{ $jabatan->urutan }}
                                </td>
                                <td x-show="!edit" class="text-sm">
                                    {{ $jabatan->users_count }} user
                                </td>
                                <td x-show="!edit">
                                    @if ($jabatan->is_active)
                                        <span class="badge badge-approved">Aktif</span>
                                    @else
                                        <span class="badge badge-cancelled">Nonaktif</span>
                                    @endif
                                </td>
                                <td x-show="!edit" class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="edit = true"
                                            class="btn-secondary btn-sm">Edit</button>
                                        @if ($jabatan->users_count === 0 && !$jabatan->is_lainnya)
                                            <form method="POST" action="{{ route('admin.jabatan.destroy', $jabatan) }}"
                                                onsubmit="return confirm('Hapus jabatan ini?')">
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
            @if ($jabatans->hasPages())
                <div class="px-4 py-3 border-t border-surface-border">
                    {{ $jabatans->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
