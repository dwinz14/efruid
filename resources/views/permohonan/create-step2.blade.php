@extends('layouts.app')

@section('title', 'Isi Data Permohonan')
@section('page-title', 'Buat Permohonan')
{{-- Lebar penuh: kolom kiri = isian utama, kolom kanan (xl ke atas) = data pemohon & persetujuan --}}

@section('content')
    @php
        // User ID USSI: terisi otomatis dari NIK, tetapi boleh diubah pengguna.
        $nik = $user->nik;
        $derivedUserId = strlen($nik) > 3 ? substr($nik, 0, 2) . substr($nik, 5) : $nik;
        $userIdValue = old('user_id_ussi', $draft?->user_id_ussi ?? $derivedUserId);

        // Nilai awal form: old input -> draft -> default
        $oldJenis = old('jenis_permohonan', $draft?->jenis_permohonan?->value ?? 'pendaftaran');
        $oldTipe = old('tipe_perubahan', $draft?->tipe_perubahan?->value ?? '');
        $oldTglMulai = old('tgl_mulai', $draft?->tgl_mulai?->format('Y-m-d') ?? '');
        $oldAccess = old('access_level', $draft?->access_level?->value ?? 'USER');

        $jenisHelp = [
            'pendaftaran' => 'Mendaftarkan akses baru.',
            'perubahan' => 'Mengubah jabatan atau hak akses.',
            'nonaktif' => 'Menonaktifkan akses mulai tanggal tertentu.',
        ];

        $accessHelp = [
            'USER' => 'Hak akses standar',
            'DIREKSI' => 'Hak akses level Direksi',
            'ADMINISTRATOR' => 'Hak akses penuh sistem',
        ];

        $flowHint =
            $formType === 'rangkap'
                ? 'Untuk Rangkap Jabatan, persetujuan Direktur Utama juga diperlukan sebelum dieksekusi IT.'
                : 'Setelah disetujui atasan, permohonan dieksekusi oleh IT.';

        // Preview membutuhkan atasan. Tanpa kandidat atasan (dan bukan Dirut), tombol dikunci.
        $previewBlocked = !$pemohonIsDirut && $atasans->isEmpty();

        $profil = [
            ['Nama', $user->name, false],
            ['NIK', $user->nik, true],
            ['Jabatan', $user->jabatan_label, false],
            ['Kantor', $user->kantor?->label ?? '—', false],
            ['Tanggal', now()->locale('id')->isoFormat('D MMMM Y'), false],
        ];

        $jabatanGroups = $jabatans->groupBy('level');
        $levelLabels = [
            1 => 'Direktur Utama',
            2 => 'Direktur',
            3 => 'Kepala Bagian / Pimpinan Cabang',
            4 => 'Kasie / Kepala Unit',
            5 => 'Staff & Pelaksana',
        ];
        $oldJabatanBaru = old('jabatan_baru', $draft?->jabatan_baru ?? '');
        $jabatanNamaList = $jabatans->pluck('nama')->toArray();
        $isCustomJabatan = $oldJabatanBaru !== '' && !in_array($oldJabatanBaru, $jabatanNamaList);
        $selectValue = $isCustomJabatan ? 'LAINNYA' : $oldJabatanBaru;
    @endphp

    <div class="space-y-6">

        @include('permohonan.partials.stepper', ['step' => 2])

        {{-- Header Halaman --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700 border border-brand-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                            Langkah 02 / 03
                        </span>
                        <span class="text-xs text-slate-400">·</span>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $formType === 'rangkap' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            <span
                                class="h-1.5 w-1.5 rounded-full {{ $formType === 'rangkap' ? 'bg-amber-500' : 'bg-brand-500' }}"></span>
                            {{ $formType === 'rangkap' ? 'Format Rangkap Jabatan' : 'Format Reguler (Tidak Rangkap)' }}
                        </span>
                        @unless ($draft)
                            <a href="{{ route('permohonan.create') }}"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-brand-700 hover:text-brand-800 hover:underline ml-1">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Ganti Format
                            </a>
                        @endunless
                    </div>

                    <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                        Lengkapi Detail Formulir Permohonan
                    </h2>
                    <p class="mt-1 text-sm text-slate-600 max-w-2xl leading-relaxed">
                        Isi tujuan permohonan dan hak akses yang dimohonkan. Informasi profil pemohon telah terisi otomatis
                        sesuai basis data kepegawaian.
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-50 border border-slate-200/80 px-3 py-1.5 text-xs text-slate-600">
                        Kolom bertanda <span class="font-bold text-red-600" aria-hidden="true">*</span> wajib diisi
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('permohonan.step3') }}" id="formStep2" @submit="submitting = 'preview'"
            x-data="permohonanForm(@js([
    'jenis' => $oldJenis,
    'tipePerubahan' => $oldTipe,
    'formType' => $formType,
    'draftUrl' => route('permohonan.draft'),
    'userId' => $userIdValue,
    'userIdDefault' => $derivedUserId,
    'tglMulai' => $oldTglMulai,
]))">
            @csrf

            <input type="hidden" name="form_type" value="{{ $formType }}">
            <input type="hidden" name="permohonan_id" value="{{ $draft?->id }}">
            <input type="hidden" name="kantor_id" value="{{ $user->kantor_id }}">

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_21rem] xl:items-start">

                {{-- ═════════════ KOLOM KIRI: isian utama ═════════════ --}}
                <div class="space-y-6">

                    {{-- ── 1. Bagian Jenis Permohonan ── --}}
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Jenis Permohonan <span
                                        class="text-red-500">*</span></h3>
                                <p class="text-xs text-slate-500">Pilih salah satu sesuai tujuan pengajuan Anda.</p>
                            </div>
                        </div>

                        <fieldset class="mt-5">
                            <legend class="sr-only">Jenis permohonan</legend>
                            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-3">
                                @foreach ($jenisList as $jenis)
                                    @php
                                        $val = $jenis->value;
                                    @endphp
                                    <label
                                        class="group relative flex cursor-pointer flex-col justify-between rounded-xl border-2 p-4 transition-all duration-200
                                        hover:border-slate-300 hover:bg-slate-50/50"
                                        :class="jenis === '{{ $val }}' ?
                                            'border-brand-600 bg-brand-50/40 shadow-xs ring-1 ring-brand-600/30' :
                                            'border-slate-200 bg-white'">
                                        <input type="radio" name="jenis_permohonan" value="{{ $val }}"
                                            x-model="jenis" @checked($oldJenis === $val) required class="sr-only">

                                        <div>
                                            <div class="flex items-center justify-between gap-2">
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors"
                                                    :class="jenis === '{{ $val }}' ? 'bg-brand-600 text-white' :
                                                        'bg-slate-100 text-slate-600 group-hover:bg-slate-200'">
                                                    @if ($val === 'pendaftaran')
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                        </svg>
                                                    @elseif ($val === 'perubahan')
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    @endif
                                                </span>

                                                <span
                                                    class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-all duration-150"
                                                    :class="jenis === '{{ $val }}' ?
                                                        'border-brand-600 bg-brand-600 text-white' :
                                                        'border-slate-300 bg-white'">
                                                    <svg x-show="jenis === '{{ $val }}'" class="h-3 w-3"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>

                                            <p class="mt-3 text-sm font-bold text-slate-900">{{ $jenis->label() }}</p>
                                            <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                                                {{ $jenisHelp[$val] ?? '' }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('jenis_permohonan')
                                <p class="field-error mt-2.5">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- ── Detail Perubahan (Tampil jika jenis === 'perubahan') ── --}}
                        <div x-show="isPerubahan" x-cloak x-transition.opacity.duration.200ms
                            class="mt-6 rounded-2xl border border-blue-200/80 bg-blue-50/30 p-5 space-y-5">

                            <div class="flex items-center gap-2 text-brand-900 border-b border-blue-100 pb-3">
                                <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <h4 class="text-sm font-bold">Konfigurasi Perubahan Jabatan</h4>
                            </div>

                            {{-- Perbandingan Jabatan Lama vs Jabatan Baru --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                {{-- Jabatan Sekarang (Readonly) --}}
                                <div>
                                    <label class="label text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Jabatan Sekarang (Aktif)
                                    </label>
                                    <div
                                        class="mt-1 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-100/80 px-3.5 py-2.5 shadow-2xs">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <svg class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span
                                                class="text-sm font-bold text-slate-800 truncate">{{ $user->jabatan_label }}</span>
                                        </div>
                                        <span
                                            class="rounded bg-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-600 flex-shrink-0">Profil</span>
                                    </div>
                                    <input type="hidden" name="jabatan_lama" value="{{ $user->jabatan_label }}">
                                </div>

                                {{-- Jabatan Baru / Yang Dirangkap --}}
                                <div x-data="{
                                    jabatanBaru: @js($selectValue),
                                    jabatanCustom: @js($isCustomJabatan ? $oldJabatanBaru : ''),
                                    get isLainnya() { return this.jabatanBaru === 'LAINNYA'; },
                                    get finalValue() { return this.isLainnya ? this.jabatanCustom : this.jabatanBaru; }
                                }">
                                    <x-field name="jabatan_baru" :label="$formType === 'rangkap' ? 'Jabatan Yang Dirangkap' : 'Jabatan Baru'" required>
                                        <div class="relative mt-1">
                                            <select id="jabatan_baru" x-model="jabatanBaru" :required="isPerubahan"
                                                aria-describedby="jabatan_baru-error"
                                                @error('jabatan_baru') aria-invalid="true" @enderror
                                                class="input w-full rounded-xl @error('jabatan_baru') input-error @enderror">
                                                <option value="">— Pilih Jabatan Tujuan —</option>
                                                @foreach ($jabatanGroups->sortKeys() as $level => $items)
                                                    @if (isset($levelLabels[$level]))
                                                        <optgroup label="{{ $levelLabels[$level] }}">
                                                            @foreach ($items->sortBy('urutan') as $jab)
                                                                <option value="{{ $jab->nama }}"
                                                                    @selected($selectValue === $jab->nama)>
                                                                    {{ $jab->nama }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                                <optgroup label="Khusus">
                                                    <option value="LAINNYA" @selected($isCustomJabatan)>Lainnya (Isi
                                                        Manual...)</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        {{-- Input Tambahan jika LAINNYA --}}
                                        <div x-show="isLainnya" x-cloak class="mt-2.5" x-transition.opacity>
                                            <input x-model="jabatanCustom" type="text" maxlength="150"
                                                aria-label="Nama Jabatan Manual"
                                                placeholder="Tuliskan nama jabatan tujuan secara lengkap..."
                                                :required="isPerubahan && isLainnya"
                                                class="input w-full rounded-xl @error('jabatan_baru') input-error @enderror">
                                            <p class="mt-1 text-xs text-slate-500">Ketikkan nama jabatan yang belum
                                                terdaftar di sistem.</p>
                                        </div>

                                        <input type="hidden" name="jabatan_baru" :value="finalValue">
                                    </x-field>
                                </div>
                            </div>

                            {{-- Tipe Perubahan & Pengaturan Tanggal --}}
                            <div
                                class="grid grid-cols-1 items-start gap-5 border-t border-blue-100/80 pt-4 md:grid-cols-20">
                                {{-- Tipe Perubahan Radio Cards --}}
                                <fieldset class="min-w-0">
                                    <legend
                                        class="label label-required text-xs font-semibold uppercase tracking-wider text-slate-700">
                                        Tipe Perubahan
                                    </legend>
                                    <div class="mt-1.5 grid grid-cols-2 gap-3">
                                        <label
                                            class="relative flex cursor-pointer items-center justify-between rounded-xl border-2 p-3 transition-all"
                                            :class="tipePerubahan === 'permanen' ? 'border-brand-600 bg-white shadow-xs' :
                                                'border-slate-200 bg-slate-50/60'">
                                            <input type="radio" name="tipe_perubahan" value="permanen"
                                                x-model="tipePerubahan" @checked($oldTipe === 'permanen')
                                                :required="isPerubahan" class="sr-only">
                                            <div>
                                                <span class="block text-sm font-bold text-slate-900">Permanen</span>
                                                <span class="block text-[11px] text-slate-500">Berlaku seterusnya</span>
                                            </div>
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full border-2"
                                                :class="tipePerubahan === 'permanen' ? 'border-brand-600 bg-brand-600' :
                                                    'border-slate-300'">
                                                <span x-show="tipePerubahan === 'permanen'"
                                                    class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                            </span>
                                        </label>

                                        <label
                                            class="relative flex cursor-pointer items-center justify-between rounded-xl border-2 p-3 transition-all"
                                            :class="tipePerubahan === 'sementara' ? 'border-brand-600 bg-white shadow-xs' :
                                                'border-slate-200 bg-slate-50/60'">
                                            <input type="radio" name="tipe_perubahan" value="sementara"
                                                x-model="tipePerubahan" @checked($oldTipe === 'sementara')
                                                :required="isPerubahan" class="sr-only">
                                            <div>
                                                <span class="block text-sm font-bold text-slate-900">Sementara</span>
                                                <span class="block text-[11px] text-slate-500">Batas waktu tertentu</span>
                                            </div>
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full border-2"
                                                :class="tipePerubahan === 'sementara' ? 'border-brand-600 bg-brand-600' :
                                                    'border-slate-300'">
                                                <span x-show="tipePerubahan === 'sementara'"
                                                    class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                            </span>
                                        </label>
                                    </div>
                                    @error('tipe_perubahan')
                                        <p class="field-error mt-2">{{ $message }}</p>
                                    @enderror
                                </fieldset>

                                {{-- Input Tanggal berdasarkan Tipe Perubahan --}}
                                <div class="min-w-0">
                                    {{-- Placeholder jika belum pilih --}}
                                    <div x-show="!isPermanen && !isSementara" x-cloak
                                        class="mt-0 rounded-xl border border-dashed border-slate-300 bg-slate-50/60 p-4 text-center text-xs text-slate-500">
                                        Pilih tipe perubahan di samping untuk mengisi tanggal efektivitas.
                                    </div>

                                    {{-- Jika Permanen --}}
                                    <div x-show="isPermanen" x-cloak x-transition.opacity.duration.150ms>
                                        <x-field name="tgl_permanen" label="Mulai Berlaku" required>
                                            <input id="tgl_permanen" type="date" name="tgl_permanen"
                                                value="{{ old('tgl_permanen', $draft?->tgl_permanen?->format('Y-m-d')) }}"
                                                min="{{ today()->format('Y-m-d') }}" :required="isPermanen"
                                                aria-describedby="tgl_permanen-error"
                                                @error('tgl_permanen') aria-invalid="true" @enderror
                                                class="input w-full rounded-xl @error('tgl_permanen') input-error @enderror">
                                        </x-field>
                                    </div>

                                    {{-- Jika Sementara (Range Tanggal) --}}
                                    <div x-show="isSementara" x-cloak x-transition.opacity.duration.150ms
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <x-field name="tgl_mulai" label="Mulai Tanggal" required>
                                            <input id="tgl_mulai" type="date" name="tgl_mulai" x-model="tglMulai"
                                                value="{{ $oldTglMulai }}" min="{{ today()->format('Y-m-d') }}"
                                                :required="isSementara" aria-describedby="tgl_mulai-error"
                                                @error('tgl_mulai') aria-invalid="true" @enderror
                                                class="input w-full rounded-xl @error('tgl_mulai') input-error @enderror">
                                        </x-field>
                                        <x-field name="tgl_selesai" label="Sampai Tanggal" required>
                                            <input id="tgl_selesai" type="date" name="tgl_selesai"
                                                value="{{ old('tgl_selesai', $draft?->tgl_selesai?->format('Y-m-d')) }}"
                                                min="{{ today()->addDay()->format('Y-m-d') }}" :min="minTglSelesai"
                                                :required="isSementara" aria-describedby="tgl_selesai-error"
                                                @error('tgl_selesai') aria-invalid="true" @enderror
                                                class="input w-full rounded-xl @error('tgl_selesai') input-error @enderror">
                                        </x-field>
                                    </div>
                                </div>
                            </div>

                            {{-- Alasan Perubahan (Opsional) --}}
                            <div class="pt-2">
                                <x-field name="alasan_perubahan" label="Alasan / Dasar Perubahan" optional>
                                    <input id="alasan_perubahan" name="alasan_perubahan" type="text"
                                        value="{{ old('alasan_perubahan', $draft?->alasan_perubahan) }}"
                                        placeholder="Contoh: Rotasi Teller ke CS." class="input w-full rounded-xl">
                                </x-field>
                            </div>
                        </div>

                        {{-- ── Detail Nonaktif (Tampil jika jenis === 'nonaktif') ── --}}
                        <div x-show="isNonaktif" x-cloak x-transition.opacity.duration.200ms
                            class="mt-6 rounded-2xl border border-red-200/80 bg-red-50/40 p-5 space-y-4">
                            <div class="flex items-center gap-2.5 text-red-900 border-b border-red-100 pb-3">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold">Penonaktifan Hak Akses</h4>
                                    <p class="text-xs text-red-700/80">Seluruh hak akses user akan dibekukan terhitung
                                        sejak tanggal yang ditentukan.</p>
                                </div>
                            </div>

                            <div class="sm:max-w-xs">
                                <x-field name="tgl_nonaktif" label="Mulai Tanggal Nonaktif" required>
                                    <input id="tgl_nonaktif" type="date" name="tgl_nonaktif"
                                        value="{{ old('tgl_nonaktif', $draft?->tgl_nonaktif?->format('Y-m-d')) }}"
                                        min="{{ today()->format('Y-m-d') }}" :required="isNonaktif"
                                        aria-describedby="tgl_nonaktif-error"
                                        @error('tgl_nonaktif') aria-invalid="true" @enderror
                                        class="input w-full rounded-xl @error('tgl_nonaktif') input-error @enderror">
                                </x-field>
                            </div>
                        </div>
                    </div>

                    {{-- ── 2. Bagian Hak Akses USSI ── --}}
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Hak Akses USSI <span
                                        class="text-red-500">*</span></h3>
                                <p class="text-xs text-slate-500">Pilih tingkat otorisasi (access level) pada Core Banking
                                    USSI.</p>
                            </div>
                        </div>

                        <fieldset class="mt-5">
                            <legend class="sr-only">Hak akses USSI</legend>
                            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-3">
                                @foreach ($accessLevels as $level)
                                    <x-choice-card name="access_level" :value="$level->value" :label="$level->label()"
                                        :description="$accessHelp[$level->value] ?? null" :checked="$oldAccess === $level->value" required />
                                @endforeach
                            </div>
                            @error('access_level')
                                <p class="field-error mt-2.5">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                </div>

                {{-- ═════════════ KOLOM KANAN: data pemohon & persetujuan (menempel saat scroll di layar lebar) ═════════════ --}}
                <aside aria-label="Data pemohon dan persetujuan"
                    class="custom-scrollbar space-y-6 xl:sticky xl:top-4 xl:max-h-[calc(100vh-8rem)] xl:overflow-y-auto">

                    {{-- ── 1. Kartu Identitas Pemohon ── --}}
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3.5">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-brand-600 text-white font-bold text-sm shadow-xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span
                                        class="font-mono text-[11px] font-semibold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                        {{ $user->nik }}
                                    </span>
                                    <span class="text-xs text-slate-400">·</span>
                                    <span
                                        class="text-xs text-slate-500 truncate">{{ $user->kantor?->nama ?? 'Pusat' }}</span>
                                </div>
                            </div>
                        </div>

                        <dl class="mt-3.5 space-y-2 text-xs">
                            <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                                <dt class="text-slate-500">Jabatan Aktif</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $user->jabatan_label }}</dd>
                            </div>
                            <div class="flex justify-between gap-2 py-1 border-b border-slate-50">
                                <dt class="text-slate-500">Kantor Penempatan</dt>
                                <dd class="font-semibold text-slate-800 text-right">{{ $user->kantor?->label ?? '—' }}
                                </dd>
                            </div>
                            <div class="flex justify-between gap-2 py-1">
                                <dt class="text-slate-500">Tanggal Pengajuan</dt>
                                <dd class="font-semibold text-slate-800 text-right">
                                    {{ now()->locale('id')->isoFormat('D MMMM Y') }}</dd>
                            </div>
                        </dl>

                        {{-- User ID USSI Input --}}
                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <x-field name="user_id_ussi" label="User ID (Sistem USSI)" required
                                hint="Pastikan User ID benar">
                                <div class="relative mt-1">
                                    <input id="user_id_ussi" name="user_id_ussi" type="text" x-model="userId"
                                        value="{{ $userIdValue }}" maxlength="30" pattern="[A-Za-z0-9_-]+"
                                        title="Hanya huruf, angka, garis bawah (_), dan strip (-)" autocomplete="off"
                                        spellcheck="false" required
                                        aria-describedby="user_id_ussi-hint user_id_ussi-error"
                                        @error('user_id_ussi') aria-invalid="true" @enderror
                                        class="input w-full font-mono text-sm tracking-wide rounded-xl @error('user_id_ussi') input-error @enderror">
                                </div>
                                <button type="button" x-show="userIdChanged" x-cloak @click="resetUserId()"
                                    class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold text-brand-700 hover:text-brand-800 hover:underline">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Kembalikan User ID default
                                </button>
                            </x-field>
                        </div>
                    </div>

                    {{-- ── 2. Kartu Otorisasi Persetujuan ── --}}
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <h3 class="text-sm font-bold text-slate-900">Alur Persetujuan Dokumen</h3>
                        </div>

                        <div class="mt-4">
                            @if ($pemohonIsDirut)
                                <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-3.5 text-xs text-blue-900">
                                    <p class="font-bold">Pemohon adalah Direktur Utama</p>
                                    <p class="mt-1 text-blue-800/90 leading-relaxed">
                                        Permohonan ini tidak memerlukan persetujuan atasan dan akan langsung diteruskan ke
                                        tim IT untuk eksekusi.
                                    </p>
                                </div>
                                <input type="hidden" name="atasan_id" value="">
                            @elseif ($atasans->isEmpty())
                                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs text-amber-900">
                                    <div class="flex items-start gap-2">
                                        <svg class="h-4 w-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div>
                                            <p class="font-bold">Belum Ada Atasan Tersedia</p>
                                            <p class="mt-1 leading-relaxed text-amber-800">
                                                Belum ditemukan pejabat dengan jabatan yang sesuai di kantor ini. Harap
                                                hubungi Administrator IT.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    $selectedAtasan = old(
                                        'atasan_id',
                                        $draft?->atasan_id ?? ($atasans->count() === 1 ? $atasans->first()->id : null),
                                    );
                                @endphp
                                <x-field name="atasan_id" label="Atasan Yang Menyetujui" required :hint="$flowHint">
                                    <div class="relative mt-1">
                                        <select id="atasan_id" name="atasan_id" required
                                            aria-describedby="atasan_id-hint atasan_id-error"
                                            @error('atasan_id') aria-invalid="true" @enderror
                                            class="input w-full rounded-xl @error('atasan_id') input-error @enderror">
                                            <option value="">— Pilih Atasan Penyetuju —</option>
                                            @foreach ($atasans as $atasan)
                                                <option value="{{ $atasan->id }}" @selected((string) $selectedAtasan === (string) $atasan->id)>
                                                    {{ $atasan->name }} — {{ $atasan->jabatan?->nama ?? '—' }}@if ($atasan->kantor_id !== $user->kantor_id)
                                                        ({{ $atasan->kantor?->nama }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </x-field>
                            @endif
                        </div>

                        {{-- Flow Steps visual badge --}}
                        <div class="mt-4 border-t border-slate-100 pt-3">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tahapan Pemrosesan</p>
                            <div class="mt-2 space-y-2 text-xs">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-100 text-brand-700 font-bold text-[10px]">1</span>
                                    <span>Pembuatan oleh Pemohon</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-slate-700 font-bold text-[10px]">2</span>
                                    <span>Persetujuan Atasan Langsung</span>
                                </div>
                                @if ($formType === 'rangkap')
                                    <div class="flex items-center gap-2 text-slate-700">
                                        <span
                                            class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">3</span>
                                        <span>Persetujuan Direktur Utama</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 text-slate-500">
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-slate-400 font-bold text-[10px]">{{ $formType === 'rangkap' ? '4' : '3' }}</span>
                                    <span>Eksekusi Sistem oleh IT</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>

            {{-- ══════════════════ BILAH AKSI STICKY DI BAWAH ══════════════════ --}}
            <div class="sticky bottom-4 z-20 mt-8">
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200/80 bg-white/95 p-4 shadow-lg backdrop-blur-md">
                    <div>
                        <a href="{{ $draft ? route('permohonan.index') : route('permohonan.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-xs transition-colors hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Tombol Simpan Draft --}}
                        <button type="button" @click="submitDraft()" :disabled="busy"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-xs transition-all hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 disabled:opacity-50">
                            <svg x-show="submitting !== 'draft'" class="h-4 w-4 text-slate-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <x-spinner x-show="submitting === 'draft'" x-cloak />
                            <span x-text="submitting === 'draft' ? 'Menyimpan draft…' : 'Simpan Draft'">Simpan Draft</span>
                        </button>

                        {{-- Tombol Lanjut ke Preview --}}
                        <button type="submit" :disabled="busy || @js($previewBlocked)"
                            @if ($previewBlocked) disabled title="Pilih atasan terlebih dahulu sebelum melanjutkan" @endif
                            class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-brand-600/30 transition-all hover:bg-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 disabled:opacity-50">
                            <x-spinner x-show="submitting === 'preview'" x-cloak />
                            <span x-text="submitting === 'preview' ? 'Menyiapkan preview…' : 'Lanjut ke Pratinjau'">Lanjut
                                ke Pratinjau</span>
                            <svg x-show="submitting !== 'preview'" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
