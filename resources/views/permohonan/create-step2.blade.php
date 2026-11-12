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
            'USER' => 'Hak akses standar untuk operasional sehari-hari',
            'DIREKSI' => 'Hak akses level Direksi untuk fitur pengawasan',
            'ADMINISTRATOR' => 'Hak akses penuh untuk konfigurasi dan manajemen sistem',
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

        {{-- ── Header: judul + jenis form (kiri), stepper (kanan di layar lebar) ── --}}
        <div class="border-b border-slate-200 pb-5 xl:flex xl:items-end xl:justify-between xl:gap-8">
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">Permohonan baru</p>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5">
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Lengkapi detail permohonan</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-0.5 text-sm font-medium text-slate-700">
                        {{ $formType === 'rangkap' ? 'Rangkap Jabatan' : 'Tidak Rangkap Jabatan' }}
                    </span>
                    {{-- Jenis form tidak bisa diganti pada draft yang sudah tersimpan --}}
                    @unless ($draft)
                        <a href="{{ route('permohonan.create') }}"
                            class="rounded text-sm font-medium text-brand-700 hover:text-brand-800 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                            Ganti
                        </a>
                    @endunless
                </div>
                <p class="mt-1.5 text-sm leading-6 text-slate-600">
                    Informasi profil telah terisi otomatis. Kolom bertanda <span class="font-semibold text-red-600"
                        aria-hidden="true">*</span>
                    <span class="sr-only">bintang</span> wajib diisi.
                </p>
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
                <div class="space-y-5">

                    {{-- Jenis permohonan --}}
                    <x-form-section title="Jenis Permohonan" description="Pilih sesuai tujuan permohonan Anda.">
                        <fieldset>
                            <legend class="sr-only">Jenis permohonan</legend>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                @foreach ($jenisList as $jenis)
                                    <x-choice-card name="jenis_permohonan" :value="$jenis->value" :label="$jenis->label()"
                                        :description="$jenisHelp[$jenis->value] ?? null" model="jenis" :checked="$oldJenis === $jenis->value" required />
                                @endforeach
                            </div>
                            @error('jenis_permohonan')
                                <p class="field-error mt-2">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- ── Detail perubahan ── --}}
                        <div x-show="isPerubahan" x-cloak x-transition.opacity.duration.150ms
                            class="mt-5 space-y-5 border-t border-slate-200 pt-5">
                            <h4 class="text-sm font-semibold text-slate-900">Detail perubahan</h4>

                            {{-- Jabatan sekarang -> jabatan baru --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <p class="label">Jabatan sekarang</p>
                                    <p
                                        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800">
                                        {{ $user->jabatan_label }}</p>
                                    <input type="hidden" name="jabatan_lama" value="{{ $user->jabatan_label }}">
                                    <p class="mt-1 text-xs text-slate-600">Dari profil Anda.</p>
                                </div>

                                <div x-data="{
                                    jabatanBaru: @js($selectValue),
                                    jabatanCustom: @js($isCustomJabatan ? $oldJabatanBaru : ''),
                                    get isLainnya() { return this.jabatanBaru === 'LAINNYA'; },
                                    get finalValue() { return this.isLainnya ? this.jabatanCustom : this.jabatanBaru; }
                                }">
                                    <x-field name="jabatan_baru" :label="$formType === 'rangkap' ? 'Jabatan yang dirangkap' : 'Jabatan baru'" required>
                                        <select id="jabatan_baru" x-model="jabatanBaru" :required="isPerubahan"
                                            aria-describedby="jabatan_baru-error"
                                            @error('jabatan_baru') aria-invalid="true" @enderror
                                            class="input @error('jabatan_baru') input-error @enderror">
                                            <option value="">— Pilih jabatan —</option>
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
                                            <optgroup label="Lainnya">
                                                <option value="LAINNYA" @selected($isCustomJabatan)>Lainnya (isi manual)
                                                </option>
                                            </optgroup>
                                        </select>

                                        <div x-show="isLainnya" x-cloak class="mt-2">
                                            <input x-model="jabatanCustom" type="text" maxlength="150"
                                                aria-label="Jabatan (isi manual)"
                                                placeholder="Tuliskan jabatan secara lengkap..."
                                                :required="isPerubahan && isLainnya"
                                                class="input @error('jabatan_baru') input-error @enderror">
                                            <p class="mt-1 text-xs text-slate-600">Isi jabatan yang tidak ada di daftar di
                                                atas.</p>
                                        </div>

                                        <input type="hidden" name="jabatan_baru" :value="finalValue">
                                    </x-field>
                                </div>
                            </div>

                            {{-- Tipe perubahan (kiri) + tanggal yang bergantung padanya (kanan) --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <fieldset>
                                    <legend class="label label-required">Tipe perubahan</legend>
                                    <div class="mt-1 grid grid-cols-1 gap-3">
                                        <x-choice-card name="tipe_perubahan" value="permanen" label="Permanen"
                                            description="Berlaku seterusnya" model="tipePerubahan" :checked="$oldTipe === 'permanen'"
                                            x-bind:required="isPerubahan" />
                                        <x-choice-card name="tipe_perubahan" value="sementara" label="Sementara"
                                            description="Ada batas waktu" model="tipePerubahan" :checked="$oldTipe === 'sementara'"
                                            x-bind:required="isPerubahan" />
                                    </div>
                                    @error('tipe_perubahan')
                                        <p class="field-error mt-2">{{ $message }}</p>
                                    @enderror
                                </fieldset>

                                <div>
                                    {{-- Petunjuk sebelum tipe dipilih --}}
                                    <p x-show="!isPermanen && !isSementara" x-cloak
                                        class="rounded-lg border border-dashed border-slate-300 px-3 py-6 text-center text-sm text-slate-600 md:mt-6">
                                        Pilih tipe perubahan untuk mengisi tanggal.
                                    </p>

                                    {{-- Tanggal permanen --}}
                                    <div x-show="isPermanen" x-cloak x-transition.opacity.duration.150ms>
                                        <x-field name="tgl_permanen" label="Mulai berlaku" required>
                                            <input id="tgl_permanen" type="date" name="tgl_permanen"
                                                value="{{ old('tgl_permanen', $draft?->tgl_permanen?->format('Y-m-d')) }}"
                                                min="{{ today()->format('Y-m-d') }}" :required="isPermanen"
                                                aria-describedby="tgl_permanen-error"
                                                @error('tgl_permanen') aria-invalid="true" @enderror
                                                class="input w-full @error('tgl_permanen') input-error @enderror">
                                        </x-field>
                                    </div>

                                    {{-- Tanggal sementara --}}
                                    <div x-show="isSementara" x-cloak x-transition.opacity.duration.150ms class="space-y-4">
                                        <x-field name="tgl_mulai" label="Mulai tanggal" required>
                                            <input id="tgl_mulai" type="date" name="tgl_mulai" x-model="tglMulai"
                                                value="{{ $oldTglMulai }}" min="{{ today()->format('Y-m-d') }}"
                                                :required="isSementara" aria-describedby="tgl_mulai-error"
                                                @error('tgl_mulai') aria-invalid="true" @enderror
                                                class="input w-full @error('tgl_mulai') input-error @enderror">
                                        </x-field>
                                        <x-field name="tgl_selesai" label="Sampai tanggal" required>
                                            <input id="tgl_selesai" type="date" name="tgl_selesai"
                                                value="{{ old('tgl_selesai', $draft?->tgl_selesai?->format('Y-m-d')) }}"
                                                min="{{ today()->addDay()->format('Y-m-d') }}" :min="minTglSelesai"
                                                :required="isSementara" aria-describedby="tgl_selesai-error"
                                                @error('tgl_selesai') aria-invalid="true" @enderror
                                                class="input w-full @error('tgl_selesai') input-error @enderror">
                                        </x-field>
                                    </div>
                                </div>
                            </div>

                            {{-- Alasan (opsional) di bagian akhir --}}
                            <x-field name="alasan_perubahan" label="Alasan perubahan" optional>
                                <input id="alasan_perubahan" name="alasan_perubahan" type="text"
                                    value="{{ old('alasan_perubahan', $draft?->alasan_perubahan) }}"
                                    placeholder="Contoh: Promosi jabatan, mutasi, dll." class="input">
                            </x-field>
                        </div>

                        {{-- ── Detail nonaktif ── --}}
                        <div x-show="isNonaktif" x-cloak x-transition.opacity.duration.150ms
                            class="mt-5 space-y-5 border-t border-slate-200 pt-5">
                            <h4 class="text-sm font-semibold text-slate-900">Detail nonaktif</h4>
                            <x-field name="tgl_nonaktif" label="Mulai tanggal nonaktif" required>
                                <input id="tgl_nonaktif" type="date" name="tgl_nonaktif"
                                    value="{{ old('tgl_nonaktif', $draft?->tgl_nonaktif?->format('Y-m-d')) }}"
                                    min="{{ today()->format('Y-m-d') }}" :required="isNonaktif"
                                    aria-describedby="tgl_nonaktif-error"
                                    @error('tgl_nonaktif') aria-invalid="true" @enderror
                                    class="input w-full sm:w-56 @error('tgl_nonaktif') input-error @enderror">
                            </x-field>
                        </div>
                    </x-form-section>

                    {{-- Hak akses USSI --}}
                    <x-form-section title="Hak Akses USSI" description="Access level yang dimohonkan.">
                        <fieldset>
                            <legend class="sr-only">Hak akses USSI</legend>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                @foreach ($accessLevels as $level)
                                    <x-choice-card name="access_level" :value="$level->value" :label="$level->label()"
                                        :description="$accessHelp[$level->value] ?? null" :checked="$oldAccess === $level->value" required />
                                @endforeach
                            </div>
                            @error('access_level')
                                <p class="field-error mt-3">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </x-form-section>
                </div>

                {{-- ═════════════ KOLOM KANAN: data pemohon & persetujuan (menempel saat scroll di layar lebar) ═════════════ --}}
                <aside aria-label="Data pemohon dan persetujuan"
                    class="custom-scrollbar space-y-5 xl:sticky xl:top-4 xl:-m-1 xl:max-h-[calc(100vh-11rem)] xl:overflow-y-auto xl:p-1">

                    {{-- Data pemohon (ringkas) + User ID yang bisa diubah --}}
                    <x-form-section title="Data Pemohon">
                        <dl class="space-y-2.5 text-sm">
                            @foreach ($profil as [$label, $value, $mono])
                                <div class="flex items-baseline justify-between gap-4">
                                    <dt class="flex-shrink-0 text-slate-600">{{ $label }}</dt>
                                    <dd
                                        class="min-w-0 break-words text-right font-medium text-slate-900 {{ $mono ? 'font-mono tracking-wide' : '' }}">
                                        {{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        <div class="mt-4 border-t border-slate-200 pt-4">
                            <x-field name="user_id_ussi" label="User ID (USSI)" required hint="Otomatis, bisa diubah.">
                                <input id="user_id_ussi" name="user_id_ussi" type="text" x-model="userId"
                                    value="{{ $userIdValue }}" maxlength="30" pattern="[A-Za-z0-9_-]+"
                                    title="Hanya huruf, angka, garis bawah (_), dan strip (-)" autocomplete="off"
                                    spellcheck="false" required aria-describedby="user_id_ussi-hint user_id_ussi-error"
                                    @error('user_id_ussi') aria-invalid="true" @enderror
                                    class="input font-mono tracking-wide @error('user_id_ussi') input-error @enderror">
                                <button type="button" x-show="userIdChanged" x-cloak @click="resetUserId()"
                                    class="mt-1 rounded text-sm font-medium text-brand-700 hover:text-brand-800 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                                    Kembalikan otomatis
                                </button>
                            </x-field>
                        </div>
                    </x-form-section>

                    {{-- Persetujuan --}}
                    <x-form-section title="Persetujuan">
                        @if ($pemohonIsDirut)
                            <x-alert type="info" title="Anda adalah Direktur Utama">
                                Permohonan Anda akan langsung diteruskan ke IT untuk dieksekusi tanpa perlu persetujuan
                                atasan.
                            </x-alert>
                            <input type="hidden" name="atasan_id" value="">
                        @elseif ($atasans->isEmpty())
                            <x-alert type="warning" title="Belum ada atasan tersedia">
                                Belum ada user dengan jabatan yang sesuai di kantor ini. Hubungi administrator untuk
                                menambahkannya.
                            </x-alert>
                        @else
                            @php
                                // Bila hanya ada satu kandidat, pilih otomatis (mengurangi satu langkah).
                                $selectedAtasan = old(
                                    'atasan_id',
                                    $draft?->atasan_id ?? ($atasans->count() === 1 ? $atasans->first()->id : null),
                                );
                            @endphp
                            <x-field name="atasan_id" label="Atasan yang menyetujui" required :hint="$flowHint">
                                <select id="atasan_id" name="atasan_id" required
                                    aria-describedby="atasan_id-hint atasan_id-error"
                                    @error('atasan_id') aria-invalid="true" @enderror
                                    class="input @error('atasan_id') input-error @enderror">
                                    <option value="">— Pilih atasan —</option>
                                    @foreach ($atasans as $atasan)
                                        <option value="{{ $atasan->id }}" @selected((string) $selectedAtasan === (string) $atasan->id)>
                                            {{ $atasan->name }} — {{ $atasan->jabatan?->nama ?? '—' }}@if ($atasan->kantor_id !== $user->kantor_id)
                                                ({{ $atasan->kantor?->nama }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </x-field>
                        @endif
                    </x-form-section>
                </aside>
            </div>

            {{-- ── Bilah aksi (sticky di bawah) ── --}}
            <x-wizard-actions>
                <x-slot:back>
                    <a href="{{ $draft ? route('permohonan.index') : route('permohonan.create') }}"
                        class="btn btn-ghost min-h-11">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        Kembali
                    </a>
                </x-slot:back>

                <button type="button" @click="submitDraft()" :disabled="busy" class="btn btn-secondary min-h-11">
                    <x-spinner x-show="submitting === 'draft'" x-cloak />
                    <span x-text="submitting === 'draft' ? 'Menyimpan…' : 'Simpan draft'">Simpan draft</span>
                </button>

                <button type="submit" :disabled="busy || @js($previewBlocked)"
                    @if ($previewBlocked) disabled title="Belum ada atasan yang bisa dipilih" @endif
                    class="btn btn-primary min-h-11">
                    <x-spinner x-show="submitting === 'preview'" x-cloak />
                    <span x-text="submitting === 'preview' ? 'Menyiapkan preview…' : 'Lanjut ke preview'">Lanjut ke
                        preview</span>
                </button>
            </x-wizard-actions>
        </form>
    </div>
@endsection
