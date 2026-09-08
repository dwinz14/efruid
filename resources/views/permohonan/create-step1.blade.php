@extends('layouts.app')

@section('title', 'Buat Permohonan')
@section('page-title', 'Buat Permohonan')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Stepper --}}
        @include('permohonan.partials.stepper', ['step' => 1])

        <a href="{{ route('permohonan.index') }}"
            class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
            </svg>
            Kembali
        </a>

        {{-- Intro Header --}}
        <div class="text-center pt-2">
            <h2 class="text-xl font-bold text-slate-800">Pilih Format FRUID</h2>
            <p class="mt-1.5 text-sm text-slate-500">
                Pilih tipe form yang sesuai dengan kebutuhan permohonan anda. <br class="hidden sm:block">
                Ini menentukan alur persetujuan dan penandatanganan dokumen.
            </p>
        </div>

        {{-- Card Grid Pilihan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- ── Tidak Rangkap Jabatan (Normal) ── --}}
            <a href="{{ route('permohonan.step2', ['form_type' => 'normal']) }}"
                class="group relative flex flex-col gap-4 p-6 bg-white border-2 border-slate-200 rounded-2xl
                  hover:border-brand-400 hover:shadow-lg hover:shadow-brand-500/10
                  hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">

                {{-- Recommended badge --}}
                <div class="absolute top-4 right-4">
                    <span
                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                             bg-brand-50 text-brand-600 border border-brand-200">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Umum
                    </span>
                </div>

                {{-- Icon --}}
                <div
                    class="w-14 h-14 rounded-2xl bg-brand-50 group-hover:bg-brand-100 flex items-center justify-center
                        transition-colors duration-200 flex-shrink-0">
                    <svg class="w-7 h-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <p class="font-bold text-slate-800 text-base group-hover:text-brand-700 transition-colors">
                        Tidak Rangkap Jabatan
                    </p>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                        Untuk karyawan dengan satu penugasan.
                    </p>

                    {{-- TTD info --}}
                    <div class="mt-4">
                        <p class="mb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Alur Persetujuan
                        </p>

                        <!-- Container Utama: Lebih compact dengan gap yang rapat -->
                        <div class="flex items-center gap-1 overflow-x-auto text-xs">

                            <!-- Step 1: Selesai -->
                            <div
                                class="inline-flex shrink-0 items-center gap-1 rounded border border-slate-200 bg-white px-2 py-1 text-slate-500">
                                <span class="font-medium">Pemohon</span>
                            </div>

                            <!-- Connector -->
                            <svg class="h-3 w-3 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                            <!-- Step 2: Selesai -->
                            <div
                                class="inline-flex shrink-0 items-center gap-1 rounded border border-slate-200 bg-white px-2 py-1 text-slate-500">
                                <span class="font-medium">Pimpinan</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">2 penandatangan</span>
                    <span
                        class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600
                             group-hover:gap-2 transition-all duration-200">
                        Pilih ini
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
            </a>

            {{-- ── Rangkap Jabatan ── --}}
            <a href="{{ route('permohonan.step2', ['form_type' => 'rangkap']) }}"
                class="group relative flex flex-col gap-4 p-6 bg-white border-2 border-slate-200 rounded-2xl
                  hover:border-amber-400 hover:shadow-lg hover:shadow-amber-500/10
                  hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">

                {{-- Icon --}}
                <div
                    class="w-14 h-14 rounded-2xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center
                        transition-colors duration-200 flex-shrink-0">
                    <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <p class="font-bold text-slate-800 text-base group-hover:text-amber-700 transition-colors">
                        Rangkap Jabatan
                    </p>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                        Untuk karyawan yang merangkap dua penugasan sekaligus.
                    </p>

                    {{-- TTD info --}}
                    <div class="mt-4">
                        <p class="mb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Alur Persetujuan
                        </p>

                        <!-- Container Utama: Lebih compact dengan gap yang rapat -->
                        <div class="flex items-center gap-1 overflow-x-auto text-xs">

                            <!-- Step 1: Selesai -->
                            <div
                                class="inline-flex shrink-0 items-center gap-1 rounded border border-slate-200 bg-white px-2 py-1 text-slate-500">
                                <span class="font-medium">Pemohon</span>
                            </div>

                            <!-- Connector -->
                            <svg class="h-3 w-3 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                            <!-- Step 2: Selesai -->
                            <div
                                class="inline-flex shrink-0 items-center gap-1 rounded border border-slate-200 bg-white px-2 py-1 text-slate-500">
                                <span class="font-medium">Pimpinan</span>
                            </div>

                            <!-- Connector -->
                            <svg class="h-3 w-3 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                            <!-- Step 3: Aktif (Cukup gunakan border gelap/slate-800 atau teks tebal tanpa warna mentereng) -->
                            <div
                                class="inline-flex shrink-0 items-center gap-1 rounded border border-slate-200 bg-white px-2 py-1 text-slate-500">
                                <span class="font-medium">Dirut</span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">3 penandatangan</span>
                    <span
                        class="inline-flex items-center gap-1 text-sm font-semibold text-amber-600
                             group-hover:gap-2 transition-all duration-200">
                        Pilih ini
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
            </a>

        </div>

        {{-- Helper note --}}
        <div class="flex items-start gap-3 px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl">
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pilihan anda masih ragu ?. Silakan berkonsultasi dengan atasan atau tim IT terlebih dahulu.
            </p>
        </div>

    </div>
@endsection
