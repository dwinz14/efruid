@extends('layouts.app')

@section('title', 'Isi Data Permohonan')
@section('page-title', 'Buat Permohonan')

@section('content')
    <div class="max-w-3xl mx-auto">

        @include('permohonan.partials.stepper', ['step' => 2])

        <form method="POST" action="{{ route('permohonan.step3') }}" class="mt-6 space-y-5" id="formStep2"
            x-data="permohonanForm({
                jenis: '{{ old('jenis_permohonan', $draft?->jenis_permohonan?->value ?? 'pendaftaran') }}',
                tipePerubahan: '{{ old('tipe_perubahan', $draft?->tipe_perubahan?->value ?? '') }}',
                formType: '{{ $formType }}',
                draftUrl: '{{ route('permohonan.draft') }}'
            })">
            @csrf

            <input type="hidden" name="form_type" value="{{ $formType }}">
            <input type="hidden" name="permohonan_id" value="{{ $draft?->id }}">

            {{-- Badge jenis form --}}
            <div class="flex items-center gap-2">
                @if ($formType === 'rangkap')
                    <span class="badge bg-amber-100 text-amber-700">Rangkap Jabatan</span>
                @else
                    <span class="badge bg-brand-100 text-brand-700">Tidak Rangkap Jabatan</span>
                @endif
                <a href="{{ route('permohonan.create') }}" class="text-xs text-slate-400 hover:text-slate-600">
                    Ganti jenis form
                </a>
            </div>

            {{-- ── Blok: Informasi Umum ── --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Informasi Umum</h3>
                </div>
                <div class="card-body grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- Tanggal — locked ke hari ini --}}
                    <div>
                        <label class="label">Tanggal Permohonan</label>
                        <input type="text" value="{{ now()->locale('id')->isoFormat('D MMMM Y') }}"
                            class="input bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                        <p class="mt-1 text-xs text-slate-400">Diisi otomatis oleh sistem</p>
                    </div>

                    {{-- Kantor --}}
                    <div>
                        <label class="label label-required">Kantor</label>
                        <select name="kantor_id" class="input @error('kantor_id') input-error @enderror" required>
                            <option value="">— Pilih Kantor —</option>
                            @foreach ($kantors as $kantor)
                                <option value="{{ $kantor->id }}" @selected(old('kantor_id', $draft?->kantor_id ?? $user->kantor_id) == $kantor->id)>
                                    {{ $kantor->label }}
                                </option>
                            @endforeach
                        </select>
                        @error('kantor_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama — dari profil, read only --}}
                    <div>
                        <label class="label">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}"
                            class="input bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                    </div>

                    {{-- NIK — dari profil, read only --}}
                    <div>
                        <label class="label">NIK Karyawan</label>
                        <input type="text" value="{{ $user->nik }}"
                            class="input bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                    </div>

                    {{-- Jabatan — dari profil, read only --}}
                    <div>
                        <label class="label">Jabatan</label>
                        <input type="text" value="{{ $user->jabatan_label }}"
                            class="input bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                    </div>

                    {{-- User ID USSI --}}
                    <div>
                        <label class="label label-required">User ID (USSI)</label>
                        <input name="user_id_ussi" type="text"
                            value="{{ old('user_id_ussi', $draft?->user_id_ussi ?? $user->nik) }}"
                            class="input font-mono @error('user_id_ussi') input-error @enderror"
                            placeholder="Contoh: AP000123456" maxlength="30" required>
                        @error('user_id_ussi')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Atasan --}}
                    <div class="sm:col-span-2">
                        <label class="label {{ !$pemohonIsDirut ? 'label-required' : '' }}">
                            Atasan yang Menyetujui
                        </label>

                        @if ($pemohonIsDirut)
                            {{-- Dirut: tidak perlu atasan --}}
                            <div class="alert-info">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0
                             11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001
                             1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <span>
                                    Sebagai Direktur Utama, permohonan Anda akan langsung
                                    diteruskan ke IT untuk dieksekusi tanpa perlu persetujuan atasan.
                                </span>
                            </div>
                            <input type="hidden" name="atasan_id" value="">
                        @elseif($atasans->isEmpty())
                            {{-- Tidak ada atasan tersedia --}}
                            <div class="alert-warning">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58
                             9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53
                             0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0
                             11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0
                             002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span>
                                    Belum ada atasan yang tersedia untuk level jabatan Anda
                                    di kantor ini. Hubungi administrator untuk menambahkan
                                    user dengan jabatan yang sesuai.
                                </span>
                            </div>
                        @else
                            {{-- Ada atasan tersedia --}}
                            <select name="atasan_id" class="input @error('atasan_id') input-error @enderror" required>
                                <option value="">— Pilih Atasan —</option>
                                @foreach ($atasans as $atasan)
                                    <option value="{{ $atasan->id }}" @selected(old('atasan_id', $draft?->atasan_id) == $atasan->id)>
                                        {{ $atasan->name }}
                                        — {{ $atasan->jabatan?->nama ?? '—' }}
                                        @if ($atasan->kantor_id !== $user->kantor_id)
                                            ({{ $atasan->kantor?->nama }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('atasan_id')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                </div>
            </div>

            {{-- ── Blok: Jenis Permohonan ── --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Jenis Permohonan</h3>
                </div>
                <div class="card-body space-y-4">

                    {{-- Radio jenis --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach ($jenisList as $jenis)
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all"
                                :class="jenis === '{{ $jenis->value }}'
                                    ?
                                    'border-brand-500 bg-brand-50' :
                                    'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="jenis_permohonan" value="{{ $jenis->value }}" x-model="jenis"
                                    class="text-brand-600 focus:ring-brand-500" required>
                                <span class="text-sm font-medium text-slate-700">{{ $jenis->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('jenis_permohonan')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    {{-- Detail Perubahan --}}
                    <div x-show="isPerubahan" x-transition class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="label label-required">Jabatan Lama</label>
                                <input name="jabatan_lama" type="text"
                                    value="{{ old('jabatan_lama', $draft?->jabatan_lama) }}"
                                    class="input @error('jabatan_lama') input-error @enderror" :required="isPerubahan">
                                @error('jabatan_lama')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="label label-required">
                                    {{ $formType === 'rangkap' ? 'Jabatan yang Dirangkap' : 'Jabatan Baru' }}
                                </label>
                                <input name="jabatan_baru" type="text"
                                    value="{{ old('jabatan_baru', $draft?->jabatan_baru) }}"
                                    class="input @error('jabatan_baru') input-error @enderror" :required="isPerubahan">
                                @error('jabatan_baru')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">Alasan Perubahan</label>
                            <input name="alasan_perubahan" type="text"
                                value="{{ old('alasan_perubahan', $draft?->alasan_perubahan) }}" class="input"
                                placeholder="Opsional">
                        </div>

                        {{-- Tipe perubahan --}}
                        <div>
                            <label class="label label-required">Tipe Perubahan</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="tipePerubahan === 'permanen' ? 'border-brand-500 bg-brand-50' :
                                        'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="tipe_perubahan" value="permanen" x-model="tipePerubahan"
                                        class="text-brand-600 focus:ring-brand-500">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Permanen</p>
                                        <p class="text-xs text-slate-400">Berlaku seterusnya</p>
                                    </div>
                                </label>
                                <label
                                    class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="tipePerubahan === 'sementara' ? 'border-brand-500 bg-brand-50' :
                                        'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="tipe_perubahan" value="sementara"
                                        x-model="tipePerubahan" class="text-brand-600 focus:ring-brand-500">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Sementara</p>
                                        <p class="text-xs text-slate-400">Ada batas waktu</p>
                                    </div>
                                </label>
                            </div>
                            @error('tipe_perubahan')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal permanen --}}
                        <div x-show="isPermanen" x-transition>
                            <label class="label label-required">Mulai Berlaku</label>
                            <input type="date" name="tgl_permanen"
                                value="{{ old('tgl_permanen', $draft?->tgl_permanen?->format('Y-m-d')) }}"
                                class="input w-48 @error('tgl_permanen') input-error @enderror" :required="isPermanen">
                            @error('tgl_permanen')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal sementara --}}
                        <div x-show="isSementara" x-transition class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="label label-required">Mulai Tanggal</label>
                                <input type="date" name="tgl_mulai"
                                    value="{{ old('tgl_mulai', $draft?->tgl_mulai?->format('Y-m-d')) }}"
                                    class="input @error('tgl_mulai') input-error @enderror" :required="isSementara">
                                @error('tgl_mulai')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="label label-required">Sampai Tanggal</label>
                                <input type="date" name="tgl_selesai"
                                    value="{{ old('tgl_selesai', $draft?->tgl_selesai?->format('Y-m-d')) }}"
                                    class="input @error('tgl_selesai') input-error @enderror" :required="isSementara">
                                @error('tgl_selesai')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Detail Nonaktif --}}
                    <div x-show="isNonaktif" x-transition class="pt-2">
                        <label class="label label-required">Mulai Tanggal Nonaktif</label>
                        <input type="date" name="tgl_nonaktif"
                            value="{{ old('tgl_nonaktif', $draft?->tgl_nonaktif?->format('Y-m-d')) }}"
                            class="input w-48 @error('tgl_nonaktif') input-error @enderror" :required="isNonaktif">
                        @error('tgl_nonaktif')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Blok: Access Level ── --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Access Level USSI</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach ($accessLevels as $level)
                            <label
                                class="flex items-start gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                            {{ old('access_level', $draft?->access_level?->value ?? 'USER') === $level->value
                                ? 'border-brand-500 bg-brand-50'
                                : 'border-slate-200 hover:border-slate-300' }}">
                                <input type="radio" name="access_level" value="{{ $level->value }}"
                                    class="mt-0.5 text-brand-600 focus:ring-brand-500" @checked(old('access_level', $draft?->access_level?->value ?? 'USER') === $level->value)
                                    required>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">{{ $level->label() }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        @if ($level === \App\Enums\AccessLevel::DIREKSI)
                                            Hak akses Direksi
                                        @elseif($level === \App\Enums\AccessLevel::ADMINISTRATOR)
                                            Hak akses IT
                                        @else
                                            Hak akses standar
                                        @endif
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('access_level')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex items-center justify-between pb-4">
                <a href="{{ route('permohonan.create') }}" class="btn-ghost">
                    ← Kembali
                </a>
                <div class="flex gap-3">
                    <button type="button" @click="submitDraft()" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Draft
                    </button>
                    <button type="submit" class="btn-primary">
                        Preview Dokumen →
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
