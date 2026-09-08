@extends('layouts.app')

@section('title', 'Preview & Submit')
@section('page-title', 'Buat Permohonan')

@section('content')
<div class="max-w-3xl mx-auto">

    @include('permohonan.partials.stepper', ['step' => 3])

    <div class="mt-6 space-y-5">

        {{-- ══════════════════════════════════════════════════
             BLOCKING STATE: Belum ada TTD digital
        ══════════════════════════════════════════════════ --}}
        @if (!$user->signature_path)
            <div class="relative overflow-hidden bg-red-50 border-2 border-red-200 rounded-2xl p-6">
                {{-- Decorative background --}}
                <div class="absolute top-0 right-0 w-32 h-32 opacity-5 pointer-events-none">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-red-800">Tanda Tangan Digital Belum Tersedia</h3>
                        <p class="text-sm text-red-700 mt-1 leading-relaxed">
                            Anda harus upload tanda tangan digital sebelum dapat submit permohonan.
                            Tanda tangan akan dicantumkan pada dokumen FRUID yang dihasilkan.
                        </p>
                        <a href="{{ route('profile.edit') }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-lg text-sm font-semibold
                                  bg-red-600 text-white hover:bg-red-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Upload Tanda Tangan di Profil
                            <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- TTD tersedia: tampilkan badge konfirmasi --}}
            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm text-emerald-700 font-medium">
                    Tanda tangan digital Anda sudah tersedia dan siap digunakan.
                </p>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════
             PREVIEW DOKUMEN
        ══════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Preview Dokumen FRUID</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Periksa kembali sebelum submit</p>
                    </div>
                </div>
                {{-- Form type badge --}}
                <div>
                    @if ($permohonan->form_type->value === 'rangkap')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                     bg-amber-100 text-amber-700 border border-amber-200">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Rangkap Jabatan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                     bg-brand-100 text-brand-700 border border-brand-200">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Tidak Rangkap Jabatan
                        </span>
                    @endif
                </div>
            </div>

            {{-- iframe Preview dengan loading state --}}
            <div class="relative" style="height: 720px;"
                 x-data="{ loaded: false }">

                {{-- Loading skeleton --}}
                <div class="absolute inset-0 bg-slate-50 flex flex-col items-center justify-center gap-4 z-10"
                     x-show="!loaded"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <p class="text-sm text-slate-500 font-medium">Memuat preview dokumen...</p>
                        {{-- Skeleton lines --}}
                        <div class="space-y-2 w-64 mt-2">
                            <div class="h-2.5 bg-slate-200 rounded-full animate-pulse"></div>
                            <div class="h-2.5 bg-slate-200 rounded-full animate-pulse w-4/5"></div>
                            <div class="h-2.5 bg-slate-200 rounded-full animate-pulse w-3/5"></div>
                        </div>
                    </div>
                </div>

                {{-- Iframe --}}
                <iframe src="{{ route('dokumen.preview', $permohonan) }}"
                        @load="loaded = true"
                        style="width: 100%; height: 100%; border: none;"
                        title="Preview Dokumen FRUID"
                        class="relative z-0">
                </iframe>
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="flex items-start gap-3 px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl">
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pastikan semua data pada dokumen sudah benar sebelum submit. Setelah dikirim, permohonan
                akan memasuki proses approval dan <strong class="text-slate-600">tidak dapat diubah</strong>.
            </p>
        </div>

        {{-- Extra bottom spacer untuk sticky bar --}}
        <div class="h-4"></div>

    </div>

    {{-- ══════════════════════════════════════════════════
         STICKY BOTTOM ACTION BAR
    ══════════════════════════════════════════════════ --}}
    <div class="sticky bottom-0 z-20 -mx-4 sm:-mx-6 lg:-mx-8 mt-0">
        <div class="border-t border-slate-200 bg-white/90 backdrop-blur-md px-4 sm:px-6 lg:px-8 py-4">
            <div class="max-w-3xl mx-auto flex items-center justify-between gap-4">

                {{-- Kiri: Edit Data --}}
                <a href="{{ route('permohonan.step2', [
                        'form_type' => $permohonan->form_type->value,
                        'draft_id'  => $permohonan->id,
                    ]) }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-slate-500
                          hover:text-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Edit Data
                </a>

                {{-- Progress label --}}
                <span class="hidden sm:block text-xs text-slate-400 font-medium">
                    Langkah <span class="font-bold text-slate-600">3</span> dari 3
                </span>

                {{-- Kanan: Submit --}}
                <form method="POST" action="{{ route('permohonan.submit') }}"
                      x-data="{ loading: false }"
                      @submit="loading = true">
                    @csrf
                    <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">

                    <button type="submit"
                            class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                                   transition-all focus:outline-none focus:ring-2 focus:ring-offset-2
                                   {{ $user->signature_path
                                       ? 'bg-brand-600 text-white hover:bg-brand-700 shadow-md shadow-brand-500/25 hover:shadow-lg hover:shadow-brand-500/30 focus:ring-brand-500'
                                       : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}"
                            :disabled="loading"
                            @if (!$user->signature_path) disabled title="Upload tanda tangan terlebih dahulu" @endif>

                        {{-- Spinner (saat loading) --}}
                        <svg x-show="loading"
                             class="animate-spin w-4 h-4"
                             fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>

                        {{-- Icon send (saat idle) --}}
                        <svg x-show="!loading"
                             class="w-4 h-4"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>

                        <span x-text="loading ? 'Mengirim Permohonan...' : 'Submit Permohonan'">
                            Submit Permohonan
                        </span>
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
