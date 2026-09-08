@extends('layouts.app')

@section('title', 'Isi Data Permohonan')
@section('page-title', 'Buat Permohonan')

@section('content')
    <div class="max-w-3xl mx-auto">

        @include('permohonan.partials.stepper', ['step' => 2])

        <form method="POST" action="{{ route('permohonan.step3') }}" class="mt-6" id="formStep2" x-data="permohonanForm({
            jenis: '{{ old('jenis_permohonan', $draft?->jenis_permohonan?->value ?? 'pendaftaran') }}',
            tipePerubahan: '{{ old('tipe_perubahan', $draft?->tipe_perubahan?->value ?? '') }}',
            formType: '{{ $formType }}',
            draftUrl: '{{ route('permohonan.draft') }}'
        })">
            @csrf

            <input type="hidden" name="form_type" value="{{ $formType }}">
            <input type="hidden" name="permohonan_id" value="{{ $draft?->id }}">

            {{-- ── Context Bar ── --}}
            <div class="flex items-center justify-between mb-5 px-1">
                <div class="flex items-center gap-2.5">
                    @if ($formType === 'rangkap')
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-xs font-semibold text-amber-700">Rangkap Jabatan</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand-50 border border-brand-200">
                            <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-xs font-semibold text-brand-700">Tidak Rangkap Jabatan</span>
                        </div>
                    @endif
                </div>
                <a href="{{ route('permohonan.create') }}"
                    class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                    Ganti jenis form
                </a>
            </div>

            <div class="space-y-5">

                {{-- ══════════════════════════════════════════════════
             BLOK 1: Informasi Umum
        ══════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    {{-- Card Header --}}
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Informasi Umum</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Data diisi otomatis dari profil Anda</p>
                        </div>
                        {{-- Auto-filled badge --}}
                        <div
                            class="ml-auto flex items-center gap-1.5 text-xs text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Read-only
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            {{-- Tanggal --}}
                            <div>
                                <label class="label">Tanggal Permohonan</label>
                                <div class="relative">
                                    <input type="text" value="{{ now()->locale('id')->isoFormat('D MMMM Y') }}"
                                        class="input bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9" readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Kantor --}}
                            <div>
                                <label class="label">Kantor</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->kantor?->label ?? '—' }}"
                                        class="input bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9" readonly>
                                    <input type="hidden" name="kantor_id" value="{{ $user->kantor_id }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- User ID USSI --}}
                            @php
                                $nik = $user->nik;
                                $derivedUserId = strlen($nik) > 3 ? substr($nik, 0, 2) . substr($nik, 5) : $nik;
                                $displayUserId = old('user_id_ussi', $draft?->user_id_ussi ?? $derivedUserId);
                            @endphp
                            <div>
                                <label class="label">User ID (USSI)</label>
                                <div class="relative">
                                    <input type="text" value="{{ $displayUserId }}"
                                        class="input font-mono bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9 tracking-wide"
                                        readonly>
                                    <input type="hidden" name="user_id_ussi" value="{{ $displayUserId }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div>
                                <label class="label">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->name }}"
                                        class="input bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9" readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label class="label">NIK Karyawan</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->nik }}"
                                        class="input font-mono bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9 tracking-wide"
                                        readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Jabatan --}}
                            <div>
                                <label class="label">Jabatan</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->jabatan_label }}"
                                        class="input bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9" readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Atasan yang Menyetujui --}}
                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <label class="label {{ !$pemohonIsDirut ? 'label-required' : '' }} mb-3">
                                Atasan yang Menyetujui
                            </label>

                            @if ($pemohonIsDirut)
                                <div class="flex items-start gap-3 p-4 bg-brand-50 border border-brand-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-brand-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-brand-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-brand-800">Anda adalah Direktur Utama</p>
                                        <p class="text-xs text-brand-600 mt-0.5">
                                            Permohonan Anda akan langsung diteruskan ke IT untuk dieksekusi tanpa perlu
                                            persetujuan atasan.
                                        </p>
                                    </div>
                                </div>
                                <input type="hidden" name="atasan_id" value="">
                            @elseif($atasans->isEmpty())
                                <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-800">Belum ada atasan tersedia</p>
                                        <p class="text-xs text-amber-700 mt-0.5">
                                            Belum ada user dengan jabatan yang sesuai di kantor ini. Hubungi administrator
                                            untuk menambahkannya.
                                        </p>
                                    </div>
                                </div>
                            @else
                                <select name="atasan_id" class="input @error('atasan_id') input-error @enderror" required>
                                    <option value="">— Pilih Atasan yang Menyetujui —</option>
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

                {{-- ══════════════════════════════════════════════════
             BLOK 2: Jenis Permohonan
        ══════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                        <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Jenis Permohonan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih sesuai dengan tujuan permohonan Anda</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- Radio Jenis Permohonan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach ($jenisList as $jenis)
                                @php
                                    $icons = [
                                        'pendaftaran' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>',
                                        'perubahan' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
                                        'nonaktif' =>
                                            '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                                    ];
                                    $icon = $icons[$jenis->value] ?? $icons['pendaftaran'];
                                @endphp
                                <label
                                    class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-150"
                                    :class="jenis === '{{ $jenis->value }}'
                                        ?
                                        'border-brand-500 bg-brand-50 shadow-sm shadow-brand-100' :
                                        'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                    <input type="radio" name="jenis_permohonan" value="{{ $jenis->value }}"
                                        x-model="jenis" class="sr-only" required>
                                    {{-- Custom radio visual --}}
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                        :class="jenis === '{{ $jenis->value }}'
                                            ?
                                            'border-brand-500 bg-brand-500' :
                                            'border-slate-300 bg-white'">
                                        <div class="w-2 h-2 rounded-full bg-white"
                                            x-show="jenis === '{{ $jenis->value }}'"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold"
                                            :class="jenis === '{{ $jenis->value }}' ? 'text-brand-700' : 'text-slate-700'">
                                            {{ $jenis->label() }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('jenis_permohonan')
                            <p class="field-error">{{ $message }}</p>
                        @enderror

                        {{-- ── Detail Perubahan ── --}}
                        <div x-show="isPerubahan" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1" class="space-y-5 pt-2">

                            {{-- Divider --}}
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-px bg-slate-100"></div>
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Detail
                                    Perubahan</span>
                                <div class="flex-1 h-px bg-slate-100"></div>
                            </div>

                            {{-- Jabatan Sekarang + Jabatan Baru --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Jabatan Sekarang</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $user->jabatan_label }}"
                                            class="input bg-slate-50/80 text-slate-500 cursor-not-allowed pr-9" readonly>
                                        <input type="hidden" name="jabatan_lama" value="{{ $user->jabatan_label }}">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">Dari profil Anda</p>
                                </div>

                                @php
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
                                    $isCustomJabatan =
                                        $oldJabatanBaru !== '' && !in_array($oldJabatanBaru, $jabatanNamaList);
                                    $selectValue = $isCustomJabatan ? 'LAINNYA' : $oldJabatanBaru;
                                @endphp

                                <div x-data="{
                                    jabatanBaru: '{{ $selectValue }}',
                                    jabatanCustom: '{{ $isCustomJabatan ? $oldJabatanBaru : '' }}',
                                    get isLainnya() { return this.jabatanBaru === 'LAINNYA'; },
                                    get finalValue() { return this.isLainnya ? this.jabatanCustom : this.jabatanBaru; }
                                }">
                                    <label class="label label-required">
                                        {{ $formType === 'rangkap' ? 'Jabatan yang Dirangkap' : 'Jabatan Baru' }}
                                    </label>

                                    <select x-model="jabatanBaru"
                                        class="input @error('jabatan_baru') input-error @enderror" :required="isPerubahan">
                                        <option value="">— Pilih Jabatan —</option>
                                        @foreach ($jabatanGroups->sortKeys() as $level => $items)
                                            @if (isset($levelLabels[$level]))
                                                <optgroup label="{{ $levelLabels[$level] }}">
                                                    @foreach ($items->sortBy('urutan') as $jab)
                                                        <option value="{{ $jab->nama }}" @selected($selectValue === $jab->nama)>
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

                                    {{-- Input custom jika LAINNYA --}}
                                    <div x-show="isLainnya" x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-2">
                                        <input x-model="jabatanCustom" type="text"
                                            class="input @error('jabatan_baru') input-error @enderror"
                                            placeholder="Tuliskan jabatan secara lengkap..." maxlength="150"
                                            :required="isPerubahan && isLainnya">
                                        <p class="mt-1 text-xs text-slate-400">Isi jabatan yang tidak ada di daftar di
                                            atas.</p>
                                    </div>

                                    <input type="hidden" name="jabatan_baru" :value="finalValue">

                                    @error('jabatan_baru')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Alasan Perubahan --}}
                            <div>
                                <label class="label">
                                    Alasan Perubahan
                                    <span class="text-slate-400 font-normal ml-1">(opsional)</span>
                                </label>
                                <input name="alasan_perubahan" type="text"
                                    value="{{ old('alasan_perubahan', $draft?->alasan_perubahan) }}" class="input"
                                    placeholder="Contoh: Promosi jabatan, mutasi, dll.">
                            </div>

                            {{-- Tipe Perubahan --}}
                            <div>
                                <label class="label label-required mb-3">Tipe Perubahan</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label
                                        class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-150"
                                        :class="tipePerubahan === 'permanen'
                                            ?
                                            'border-brand-500 bg-brand-50 shadow-sm shadow-brand-100' :
                                            'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                        <input type="radio" name="tipe_perubahan" value="permanen"
                                            x-model="tipePerubahan" class="sr-only">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                            :class="tipePerubahan === 'permanen' ? 'border-brand-500 bg-brand-500' :
                                                'border-slate-300 bg-white'">
                                            <div class="w-2 h-2 rounded-full bg-white"
                                                x-show="tipePerubahan === 'permanen'"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2"
                                                    :class="tipePerubahan === 'permanen' ? 'text-brand-600' : 'text-slate-400'">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-sm font-semibold"
                                                    :class="tipePerubahan === 'permanen' ? 'text-brand-700' : 'text-slate-700'">
                                                    Permanen
                                                </p>
                                            </div>
                                            <p class="text-xs text-slate-400 mt-0.5">Berlaku seterusnya</p>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-150"
                                        :class="tipePerubahan === 'sementara'
                                            ?
                                            'border-brand-500 bg-brand-50 shadow-sm shadow-brand-100' :
                                            'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                        <input type="radio" name="tipe_perubahan" value="sementara"
                                            x-model="tipePerubahan" class="sr-only">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                            :class="tipePerubahan === 'sementara' ? 'border-brand-500 bg-brand-500' :
                                                'border-slate-300 bg-white'">
                                            <div class="w-2 h-2 rounded-full bg-white"
                                                x-show="tipePerubahan === 'sementara'"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2"
                                                    :class="tipePerubahan === 'sementara' ? 'text-brand-600' : 'text-slate-400'">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm font-semibold"
                                                    :class="tipePerubahan === 'sementara' ? 'text-brand-700' : 'text-slate-700'">
                                                    Sementara
                                                </p>
                                            </div>
                                            <p class="text-xs text-slate-400 mt-0.5">Ada batas waktu</p>
                                        </div>
                                    </label>
                                </div>
                                @error('tipe_perubahan')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal Permanen --}}
                            <div x-show="isPermanen" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0">
                                <label class="label label-required">Mulai Berlaku</label>
                                <input type="date" name="tgl_permanen"
                                    value="{{ old('tgl_permanen', $draft?->tgl_permanen?->format('Y-m-d')) }}"
                                    min="{{ today()->format('Y-m-d') }}"
                                    class="input w-full sm:w-56 @error('tgl_permanen') input-error @enderror"
                                    :required="isPermanen">
                                @error('tgl_permanen')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal Sementara --}}
                            <div x-show="isSementara" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label label-required">Mulai Tanggal</label>
                                    <input type="date" name="tgl_mulai"
                                        value="{{ old('tgl_mulai', $draft?->tgl_mulai?->format('Y-m-d')) }}"
                                        min="{{ today()->format('Y-m-d') }}"
                                        class="input @error('tgl_mulai') input-error @enderror" :required="isSementara">
                                    @error('tgl_mulai')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="label label-required">Sampai Tanggal</label>
                                    <input type="date" name="tgl_selesai"
                                        value="{{ old('tgl_selesai', $draft?->tgl_selesai?->format('Y-m-d')) }}"
                                        min="{{ today()->addDay()->format('Y-m-d') }}"
                                        class="input @error('tgl_selesai') input-error @enderror" :required="isSementara">
                                    @error('tgl_selesai')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── Detail Nonaktif ── --}}
                        <div x-show="isNonaktif" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1" class="pt-2">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="flex-1 h-px bg-slate-100"></div>
                                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Detail
                                    Nonaktif</span>
                                <div class="flex-1 h-px bg-slate-100"></div>
                            </div>
                            <div>
                                <label class="label label-required">Mulai Tanggal Nonaktif</label>
                                <input type="date" name="tgl_nonaktif"
                                    value="{{ old('tgl_nonaktif', $draft?->tgl_nonaktif?->format('Y-m-d')) }}"
                                    min="{{ today()->format('Y-m-d') }}"
                                    class="input w-full sm:w-56 @error('tgl_nonaktif') input-error @enderror"
                                    :required="isNonaktif">
                                @error('tgl_nonaktif')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════
             BLOK 3: Access Level USSI
        ══════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Access Level USSI</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tentukan hak akses yang dimohonkan</p>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $accessDescriptions = [
                                'USER' => [
                                    'desc' => 'Hak akses standar untuk operasional sehari-hari',
                                    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                ],
                                'DIREKSI' => [
                                    'desc' => 'Hak akses level Direksi untuk fitur pengawasan',
                                    'icon' =>
                                        'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z',
                                ],
                                'ADMINISTRATOR' => [
                                    'desc' => 'Hak akses penuh untuk konfigurasi dan manajemen sistem',
                                    'icon' =>
                                        'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                                ],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach ($accessLevels as $level)
                                @php
                                    $isChecked =
                                        old('access_level', $draft?->access_level?->value ?? 'USER') === $level->value;
                                    $levelConfig = $accessDescriptions[$level->value] ?? ['desc' => '', 'icon' => ''];
                                @endphp
                                <label
                                    class="flex flex-col gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-150
                               {{ $isChecked
                                   ? 'border-brand-500 bg-brand-50 shadow-sm shadow-brand-100'
                                   : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                                    <input type="radio" name="access_level" value="{{ $level->value }}"
                                        class="sr-only" @checked($isChecked) required>
                                    {{-- Icon + Radio --}}
                                    <div class="flex items-center justify-between">
                                        <div
                                            class="w-9 h-9 rounded-lg flex items-center justify-center
                                     {{ $isChecked ? 'bg-brand-100' : 'bg-slate-100' }}">
                                            <svg class="w-5 h-5 {{ $isChecked ? 'text-brand-600' : 'text-slate-400' }}"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="{{ $levelConfig['icon'] }}" />
                                            </svg>
                                        </div>
                                        <div
                                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                     {{ $isChecked ? 'border-brand-500 bg-brand-500' : 'border-slate-300' }}">
                                            @if ($isChecked)
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Label & Desc --}}
                                    <div>
                                        <p
                                            class="text-sm font-semibold {{ $isChecked ? 'text-brand-700' : 'text-slate-700' }}">
                                            {{ $level->label() }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                            {{ $levelConfig['desc'] }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('access_level')
                            <p class="field-error mt-3">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Extra bottom spacer untuk sticky bar --}}
                <div class="h-4"></div>

            </div>{{-- end space-y-5 --}}

            {{-- ══════════════════════════════════════════════════
             STICKY BOTTOM ACTION BAR
        ══════════════════════════════════════════════════ --}}
            <div
                class="sticky bottom-3 z-20 mx-auto w-full max-w-4xl rounded-xl border border-slate-200/80 bg-white/95 px-3 py-2 shadow-lg shadow-slate-900/10 backdrop-blur-md sm:px-4">

                <div class="flex items-center justify-between gap-3">

                    {{-- Kiri: Back --}}
                    <a href="{{ route('permohonan.create') }}"
                        class="group inline-flex shrink-0 items-center gap-1.5 rounded-lg px-2 py-1.5 text-xs font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>

                    {{-- Progress --}}
                    <span class="hidden text-[11px] font-medium text-slate-400 sm:block">
                        Langkah <span class="font-semibold text-slate-600">2</span>
                        <span class="mx-0.5 text-slate-300">/</span>
                        3
                    </span>

                    {{-- Kanan: Actions --}}
                    <div class="flex items-center gap-1.5">

                        {{-- Simpan Draft --}}
                        <button type="button" @click="submitDraft()"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-1 active:scale-[0.98]">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Simpan Draft</span>
                        </button>

                        {{-- Preview --}}
                        <button type="submit"
                            class="group inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm shadow-brand-500/20 transition-all duration-200 hover:-translate-y-px hover:bg-brand-700 hover:shadow-md hover:shadow-brand-500/25 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-1 active:translate-y-0 active:scale-[0.98]">
                            <span>Preview Dokumen</span>
                            <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
